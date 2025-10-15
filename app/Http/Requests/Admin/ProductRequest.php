<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->is_admin;
    }

    public function rules()
    {
        return [
            // Fill in when adding product fields
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            // Add other product validation rules here
        ];
    }
}
