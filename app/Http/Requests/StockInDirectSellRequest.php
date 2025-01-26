<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockInDirectSellRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Ensure the user is authorized to make this request
        return true;  // Change as per your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'rack_id' => 'nullable|exists:racks,id',
            'qty' => 'required|numeric|min:0.1',  // Allow float values
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gte:purchase_price',  // Updated to 'gte' for greater than or equal to purchase price
            // 'expiration_date' => 'nullable|date|after:today',
            // 'alert_date' => 'nullable|date|before:expiration_date',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required and cannot be empty.',
            'product_name.string' => 'Product name must be a valid string.',
            'product_name.max' => 'Product name cannot exceed 255 characters.',

            'supplier_id.required' => 'Supplier is required.',
            'supplier_id.integer' => 'The supplier ID must be an integer.',
            'supplier_id.exists' => 'The selected supplier does not exist.',

            // rack_id
            'rack_id.exists' => 'The selected rack does not exist.',

            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a valid number.',
            'qty.min' => 'Quantity must be at least 0.1.',  // Allow float value and minimum 0.1

            'purchase_price.required' => 'Purchase price is required.',
            'purchase_price.numeric' => 'Purchase price must be a valid number.',
            'purchase_price.min' => 'Purchase price must be a positive value.',

            'sell_price.required' => 'Sell price is required.',
            'sell_price.numeric' => 'Sell price must be a valid number.',
            'sell_price.min' => 'Sell price must be a positive value.',
            'sell_price.gte' => 'Sell price must be greater than or equal to purchase price.',  // Ensure sell price >= purchase price

            // expiration_date
            // 'expiration_date.date' => 'Expiration date must be a valid date.',
            // 'expiration_date.after' => 'Expiration date must be a date after today.',

            // alert_date
            // 'alert_date.date' => 'Alert date must be a valid date.',
            // 'alert_date.before' => 'Alert date must be before the expiration date.',
        ];
    }
}
