<?php

namespace App\Http\Controllers;

use App\Mail\OrderPaymentConfirmed;
use App\Models\Order;
use App\Models\TransactionLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function initializeEspeesPayment(Request $request)
    {
        $validated = $request->validate([
            'orderId' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:500'],
            'successUrl' => ['required', 'url'],
            'failUrl' => ['required', 'url'],
        ]);

        $order = Order::with(['items', 'user'])
            ->whereKey($validated['orderId'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $walletAddress = config('services.espees.merchant_wallet');

        if (blank($walletAddress)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Espees merchant wallet is not configured.',
                'payment_url' => null,
                'method' => 'ESPEES',
            ], 422);
        }

        $finalPrice = $this->finalOrderPrice($order);

        try {
            $response = Http::timeout(config('services.espees.timeout', 45))
                ->acceptJson()
                ->asJson()
                ->post($this->espeesApiUrl('/payment/product'), [
                    'product_sku' => (string) $order->id,
                    'price' => $finalPrice,
                    'merchant_wallet' => $walletAddress,
                    'narration' => $validated['description'] ?? "Order #{$order->id}",
                    'success_url' => $validated['successUrl'],
                    'fail_url' => $validated['failUrl'],
                ]);

            $payload = $response->json() ?? [];
        } catch (\Throwable $exception) {
            Log::error('Espees payment initialization failed', [
                'order_id' => $order->id,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'status' => 'failed',
                'message' => 'Unable to initialize Espees payment. Please try again.',
                'payment_url' => null,
                'method' => 'ESPEES',
            ], 502);
        }

        $productId = data_get($payload, 'productid');
        $message = data_get($payload, 'message', 'Unable to initialize Espees payment.');
        $statusCode = (int) data_get($payload, 'statusCode');

        if ($message === 'Successfully Done' && $statusCode === 200 && filled($productId)) {
            $order->forceFill([
                'payment_id' => $productId,
                'payment_method' => 'espees',
                'payment_status' => 'pending',
            ])->save();

            TransactionLog::create([
                'order_id' => (string) $order->id,
                'transaction_id' => $productId,
                'user_id' => (string) $order->user_id,
                'status' => 'initiated',
            ]);

            return response()->json([
                'status' => 'success',
                'payment_url' => $this->espeesPaymentUrl("/pay/{$productId}"),
                'method' => 'ESPEES',
            ]);
        }

        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'payment_url' => null,
            'method' => 'ESPEES',
        ], $response->status() >= 400 ? $response->status() : 422);
    }

    public function confirmEspeesPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'string'],
            'test_mail' => ['sometimes', 'boolean'],
        ]);

        $order = Order::with(['items', 'user'])
            ->whereKey($validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($request->boolean('test_mail')) {
            $this->sendPaymentConfirmationEmail($order, true);

            return response()->json([
                'transaction_status' => 'APPROVED',
                'product_sku' => (string) $order->id,
                'status_details' => 'Test confirmation email sent.',
            ]);
        }

        if (blank($order->payment_id)) {
            throw ValidationException::withMessages([
                'order_id' => 'This order does not have an Espees payment ID.',
            ]);
        }

        try {
            $response = Http::timeout(config('services.espees.timeout', 45))
                ->acceptJson()
                ->asJson()
                ->post($this->espeesApiUrl('/payment/confirm'), [
                    'product_id' => $order->payment_id,
                ]);

            $payload = $response->json() ?? [];
        } catch (\Throwable $exception) {
            Log::error('Espees payment confirmation failed', [
                'order_id' => $order->id,
                'payment_id' => $order->payment_id,
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'transaction_status' => 'ERROR',
                'product_sku' => (string) $order->id,
                'status_details' => 'Unable to confirm Espees payment. Please try again.',
            ], 502);
        }

        $transactionStatus = data_get($payload, 'transaction_status');
        $productSku = (string) data_get($payload, 'product_sku');
        $statusDetails = data_get($payload, 'status_details');

        if ($transactionStatus === 'APPROVED' && $productSku === (string) $order->id) {
            $alreadyPaid = $order->payment_status === 'paid';

            if (! $alreadyPaid) {
                $order->forceFill([
                    'payment_status' => 'paid',
                    'amount_paid' => data_get($payload, 'price', $this->finalOrderPrice($order)),
                    'currency' => 'ESPEES',
                    'transaction_id' => $order->payment_id,
                    'transact_time' => $this->transactionTime(data_get($payload, 'transaction_date')),
                ])->save();

                TransactionLog::create([
                    'order_id' => (string) $order->id,
                    'transaction_id' => $order->payment_id,
                    'user_id' => (string) $order->user_id,
                    'status' => 'confirmed',
                ]);

                $this->sendPaymentConfirmationEmail($order->fresh(['items', 'user']));
            }

            return response()->json([
                'transaction_status' => 'APPROVED',
                'product_sku' => $productSku,
                'status_details' => $statusDetails,
            ]);
        }

        return response()->json([
            'transaction_status' => $transactionStatus,
            'product_sku' => $productSku,
            'status_details' => $statusDetails,
        ], $response->status());
    }

    private function finalOrderPrice(Order $order): float
    {
        $orderType = strtolower((string) ($order->type ?? $order->order_type ?? $order->tier ?? ''));

        if (in_array($orderType, ['double', 'shared'], true)) {
            return (float) ($order->discounted_double_price
                ?? $order->room_sharing_price
                ?? $order->total_amount);
        }

        return (float) ($order->discounted_single_price
            ?? $order->price
            ?? $order->total_amount);
    }

    private function transactionTime(?string $transactionDate): Carbon
    {
        if (blank($transactionDate)) {
            return now();
        }

        try {
            return Carbon::parse($transactionDate);
        } catch (\Throwable) {
            return now();
        }
    }

    private function sendPaymentConfirmationEmail(Order $order, bool $test = false): void
    {
        if (blank($order->user?->email)) {
            return;
        }

        Mail::to($order->user->email)
            ->send(new OrderPaymentConfirmed($order, $test));
    }

    private function espeesApiUrl(string $path): string
    {
        return rtrim(config('services.espees.api_url'), '/') . $path;
    }

    private function espeesPaymentUrl(string $path): string
    {
        return rtrim(config('services.espees.payment_url'), '/') . $path;
    }

}
