<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:product_categories,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',
            'parent_id.exists' => 'The selected category is invalid.',
        ];
    }
}
