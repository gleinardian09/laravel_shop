<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Order;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get counts
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();

        // Daily sales
        $dailySales = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 5)->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSubcategories',
            'totalOrders',
            'totalUsers',
            'dailySales',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}