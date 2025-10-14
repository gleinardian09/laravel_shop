<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        // Debug: Log the orders for current user
        \Log::info('User orders', [
            'user_id' => auth()->id(),
            'order_count' => $orders->count(),
            'order_ids' => $orders->pluck('id')->toArray()
        ]);

        return view('orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        // Debug: Log the request
        \Log::info('Order show request', [
            'user_id' => auth()->id(),
            'requested_order_id' => $orderId
        ]);

        // Find the order and ensure it belongs to the current user
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->with('items.product')
            ->first();

        if (!$order) {
            \Log::warning('Order not found or unauthorized', [
                'user_id' => auth()->id(),
                'order_id' => $orderId,
                'order_exists' => Order::where('id', $orderId)->exists(),
                'order_belongs_to_user' => Order::where('id', $orderId)->where('user_id', auth()->id())->exists()
            ]);
            
            abort(404, 'Order not found.');
        }

        return view('orders.show', compact('order'));
    }

    // ... keep the reorder method the same
}