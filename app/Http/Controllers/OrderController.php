<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Order/Index', [
            'orders' => Order::query()
                ->with('items')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->get(['id', 'user_id', 'total_amount', 'status', 'payment_status', 'payment_id', 'shipping_address', 'created_at']),
        ]);
    }

    public function checkout(Request $request)
    {
        $cartItems = $this->cartItemsForRequest($request);

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        return Inertia::render('Order/Checkout', [
            'cartItems' => array_values($cartItems),
            'cartTotal' => $this->cartTotal($cartItems),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:espees',
        ]);

        $cartItems = $this->cartItemsForRequest($request);

        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $totalAmount = $this->cartTotal($cartItems);

        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'] ?? null,
        ]);

        foreach ($cartItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => (string) $order->id,
                'product_name' => $item['name'],
                'product_image' => $item['image'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * (float)$item['price'],
            ]);
        }

        CartItem::query()
            ->where($this->cartOwner($request))
            ->delete();

        Session::forget('cart.items');
        Session::forget('cart.session_id');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'order_id' => (string) $order->id,
                'amount' => $order->total_amount,
                'description' => "Order #{$order->id}",
            ]);
        }

        return redirect()->route('order.confirmation', ['order' => $order->id])
            ->with('success', 'Your order has been placed successfully!');
    }

    public function confirmation($orderId)
    {
        $order = \App\Models\Order::with('items')->findOrFail($orderId);
        
        // Verify ownership
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return Inertia::render('Order/Confirmation', [
            'order' => $order
        ]);
    }

    private function cartOwner(Request $request): array
    {
        if ($request->user()) {
            return ['user_id' => $request->user()->id];
        }

        return ['session_id' => $request->session()->getId()];
    }

    private function cartItemsForRequest(Request $request): array
    {
        $items = CartItem::query()
            ->where($this->cartOwner($request))
            ->get()
            ->map(fn (CartItem $item) => [
                'id' => $item->product_id,
                'name' => $item->product_name,
                'price' => $item->price,
                'image' => $item->product_image,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
            ])
            ->all();

        return $items === [] ? Session::get('cart.items', []) : $items;
    }

    private function cartTotal(array $cartItems): float
    {
        return array_reduce(
            $cartItems,
            fn ($total, $item) => $total + (($item['quantity'] ?? 0) * (float) ($item['price'] ?? 0)),
            0
        );
    }
}
