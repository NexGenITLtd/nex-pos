<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Set to true or implement authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|min:11|max:14',
            'email' => 'required|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The asset name is required.',
            'contact_person.required' => 'The contact person name is required.',
            'phone.required' => 'The phone number is required.',
            'email.required' => 'The email address is required.',
            // Add more custom error messages as needed
        ];
    }
}

