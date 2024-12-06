<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization if needed
    }

    public function rules()
    {
        return [
            'bank_name' => 'required|string|max:255',
            'account_no' => 'required|unique:bank_accounts,account_no|string|max:20',
            'account_type' => 'required|string|max:50',
            'initial_balance' => 'required|numeric',
            'store_id' => 'required|exists:stores,id', // Assuming stores table has an 'id' field
        ];
    }

    public function messages()
    {
        return [
            'bank_name.required' => 'Bank name is required.',
            'account_no.required' => 'Account number is required.',
            'account_no.unique' => 'This account number is already taken.',
            'account_type.required' => 'Account type is required.',
            'initial_balance.required' => 'Initial balance is required.',
            'store_id.required' => 'Store selection is required.',
            'store_id.exists' => 'The selected store does not exist.',
        ];
    }
}
