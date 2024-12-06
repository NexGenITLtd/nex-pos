<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  // Adjust this if you want to add authorization logic.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'card_type' => 'required|string|max:255', // Example validation rule
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'card_type.required' => 'The card type field is required.',
            'card_type.string' => 'The card type must be a string.',
            'card_type.max' => 'The card type may not be greater than 255 characters.',
        ];
    }
}
