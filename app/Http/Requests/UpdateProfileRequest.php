<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Ensure this is set to true for validation to work
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:30|unique:users,phone,' . auth()->id(),
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Image validation
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'The name must be a valid string.',
            'phone.max' => 'The phone number may not be greater than 30 characters.',
            'img.image' => 'The file must be an image.',
        ];
    }
}

