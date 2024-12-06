<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust this if authorization logic is needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_sub_category_id' => 'nullable|exists:product_categories,id',
            'brand_id' => 'nullable|exists:product_brands,id',
            'unit' => 'nullable|string|exists:units,name',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
