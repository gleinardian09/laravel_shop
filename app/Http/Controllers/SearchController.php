<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $categoryId = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'newest');

        $products = Product::where('is_active', true)
            ->search($search)
            ->filterByCategory($categoryId)
            ->filterByPrice($minPrice, $maxPrice)
            ->sortBy($sort)
            ->with('category')
            ->withAvg('approvedReviews', 'rating')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $totalResults = $products->total();

        return view('search.results', compact(
            'products', 'categories', 'search', 
            'categoryId', 'minPrice', 'maxPrice', 
            'sort', 'totalResults'
        ));
    }
}