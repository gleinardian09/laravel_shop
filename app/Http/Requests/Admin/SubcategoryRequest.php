<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SubcategoryRequest extends FormRequest
{
    public function authorize()
    {
        // Only admins can make this request
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        $subcategoryId = $this->route('subcategory') ? $this->route('subcategory')->id : null;

        return [
            'name' => 'required|string|max:255|unique:subcategories,name,' . $subcategoryId,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ];
    }
}
