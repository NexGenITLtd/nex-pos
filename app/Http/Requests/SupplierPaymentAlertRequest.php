<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierPaymentAlertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow all users (adjust as necessary for your needs)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'store_id' => 'required|exists:stores,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'amount' => 'required|numeric|min:1',
            'pay_date' => 'required|date',
            'notice_date' => 'required|date|after_or_equal:pay_date',
        ];
    }
}

