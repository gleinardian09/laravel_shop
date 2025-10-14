<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', // ADDED
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'required|image|max:2048', // CHANGED from nullable to required
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string', // ADDED
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'image' => 'sometimes|image|max:2048', // CHANGED to sometimes for updates
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}