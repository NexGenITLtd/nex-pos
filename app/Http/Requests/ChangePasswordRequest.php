<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}

