<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->toString();
        $paymentStatus = $request->string('payment_status')->toString();

        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::with(['user', 'items'])
                ->when($paymentStatus, fn ($query) => $query->where('payment_status', $paymentStatus))
                ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('id', $search)
                        ->orWhere('transaction_id', 'like', "%{$search}%")
                        ->orWhere('payment_id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                }))
                ->latest()
                ->paginate(10)
                ->withQueryString(),
            'filters' => [
                'search' => $search,
                'payment_status' => $paymentStatus,
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,completed,cancelled'],
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated.');
    }
}
