<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'successful_payments' => Order::where('payment_status', 'paid')->count(),
                'failed_or_pending_payments' => Order::where('payment_status', '!=', 'paid')->count(),
                'sold_out_products' => Product::where('is_sold_out', true)->count(),
            ],
            'recentOrders' => Order::with('user')
                ->latest()
                ->take(6)
                ->get(['id', 'user_id', 'total_amount', 'status', 'payment_status', 'created_at']),
            'recentProducts' => Product::latest()
                ->take(6)
                ->get(),
        ]);
    }
}
