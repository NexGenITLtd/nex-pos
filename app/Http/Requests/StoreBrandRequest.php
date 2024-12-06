<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users to use this request
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:product_brands,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The brand name is required.',
            'name.unique' => 'This brand name already exists.',
        ];
    }
}
