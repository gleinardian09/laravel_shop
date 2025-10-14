<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ensure variables exist even if an exception occurs
        $featuredProducts = collect();
        $categories = collect();
        $newArrivals = collect();

        try {
            // Get featured products
            $featuredProducts = Product::where('is_active', true)
                ->where('stock', '>', 0)
                ->with('category')
                ->inRandomOrder()
                ->limit(8)
                ->get();

            // Get categories with product count
            $categories = Category::withCount(['products' => function($query) {
                $query->where('is_active', true);
            }])->get();

            // Get new arrivals
            $newArrivals = Product::where('is_active', true)
                ->with('category')
                ->latest()
                ->limit(6)
                ->get();

            // Debug logging
            \Log::info('HomeController Data Loaded', [
                'featured_products' => $featuredProducts->count(),
                'categories' => $categories->count(),
                'new_arrivals' => $newArrivals->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('HomeController Error: ' . $e->getMessage());
            // keep the initialized empty collections
        }

        return view('home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'newArrivals' => $newArrivals
        ]);
    }
}