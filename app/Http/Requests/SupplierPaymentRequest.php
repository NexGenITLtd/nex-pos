<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string|max:255',
            'paid_date' => 'required|date',
            'store_id' => 'required|exists:stores,id',
        ];
    }
}