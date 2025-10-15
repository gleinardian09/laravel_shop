<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use App\Http\Requests\Admin\SubcategoryRequest;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        $subcategories = Subcategory::with('category')
            ->orderBy('category_id')
            ->orderBy('order')
            ->get();

        return view('admin.subcategories.index', compact('subcategories'));
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
