<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $recentOrders = Order::where('user_id', $user->id)
            ->with('orderItems.product')
            ->latest()
            ->take(5)
            ->get();

        $pendingOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'processing'])
            ->count();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->where('status', 'completed')->sum('total_amount');
        $recentReviews = Review::where('user_id', $user->id)->with('product')->latest()->take(3)->get();

        return view('dashboard', compact(
            'recentOrders',
            'pendingOrders',
            'totalOrders',
            'totalSpent',
            'recentReviews'
        ));
    }
}