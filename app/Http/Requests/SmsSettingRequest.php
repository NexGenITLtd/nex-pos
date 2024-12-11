<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsSettingRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to false if only authorized users can make this request
    }

    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'api_key' => 'required|string|max:255',
            'api_url' => 'required|url',
            'sender_id' => 'required|string|max:255',
            'message' => 'nullable|string',
            'user_email' => 'required|email|max:255',
            'balance' => 'required|numeric|min:0',
            'sms_rate' => 'required|numeric|min:0',
            'sms_count' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'store_id.required' => 'The store selection is mandatory.',
            'api_key.required' => 'The API key is required.',
            'api_url.required' => 'The API URL is required.',
            'api_url.url' => 'The API URL must be a valid URL.',
            'sender_id.required' => 'The sender ID is required.',
            'user_email.required' => 'The user email is required.',
            'user_email.email' => 'The user email must be a valid email address.',
            'balance.required' => 'The balance is required.',
            'sms_rate.required' => 'The SMS rate is required.',
            'sms_count.required' => 'The SMS count is required.',
        ];
    }
}
