<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use App\Http\Requests\Admin\SubcategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index(Request $request) // Add Request parameter
    {
        $this->authorizeAdmin();

        $query = Subcategory::query()->with('category');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'category_order');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'order_asc':
                $query->orderBy('order', 'asc');
                break;
            case 'order_desc':
                $query->orderBy('order', 'desc');
                break;
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            default: // category_order
                $query->orderBy('category_id')->orderBy('order');
                break;
        }

        $subcategories = $query->get();
        $categories = Category::where('is_active', true)->get(); // For filter dropdown

        return view('admin.subcategories.index', compact('subcategories', 'categories'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $categories = Category::where('is_active', true)->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(SubcategoryRequest $request)
    {
        $this->authorizeAdmin();

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Subcategory::create($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    public function edit(Subcategory $subcategory)
    {
        $this->authorizeAdmin();

        $categories = Category::where('is_active', true)->get();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        $this->authorizeAdmin();

        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $subcategory->update($data);

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $this->authorizeAdmin();

        $subcategory->delete();

        return redirect()->route('admin.subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }

    /** Admin authorization helper */
    private function authorizeAdmin()
    {
        if (!auth()->user() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized action. Admin privileges required.');
        }
    }
}