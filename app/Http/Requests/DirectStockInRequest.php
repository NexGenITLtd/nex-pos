<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DirectStockInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'product_sub_category_id' => 'nullable|exists:product_categories,id',
            'store_id' => 'required|exists:stores,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand_id' => 'nullable|exists:product_brands,id',
            'unit_id' => 'nullable|exists:units,id',  // Now nullable
            'rack_id' => 'nullable|exists:racks,id',
            'qty' => 'required|numeric|min:0.1',  // Allow float values
            'purchase_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0|gte:purchase_price',  // Updated to 'gte' for greater than or equal to
            // 'expiration_date' => 'nullable|date|after:today',
            // 'alert_date' => 'nullable|date|before:expiration_date',
        ];
    }


    /**
     * Get the validation rules that apply to the request.
     */
    public function messages(): array
    {
        return [
            // product_name
            'product_name.required' => 'Product name is required.',
            'product_name.string' => 'Product name must be a valid string.',
            'product_name.max' => 'Product name cannot exceed 255 characters.',

            // product_category_id
            'product_category_id.required' => 'Category is required.',
            'product_category_id.exists' => 'The selected category does not exist.',

            // product_sub_category_id
            'product_sub_category_id.exists' => 'The selected subcategory does not exist.',

            // store_id
            'store_id.required' => 'Store is required.',
            'store_id.exists' => 'The selected store does not exist.',

            // supplier_id
            'supplier_id.exists' => 'The selected supplier does not exist.',

            // brand_id
            'brand_id.exists' => 'The selected brand does not exist.',

            // unit_id
            'unit_id.exists' => 'The selected unit does not exist.',

            // rack_id
            'rack_id.exists' => 'The selected rack does not exist.',

            // qty
            'qty.required' => 'Quantity is required.',
            'qty.numeric' => 'Quantity must be a valid number.',
            'qty.min' => 'Quantity must be at least 0.1.',  // Updated for float

            // purchase_price
            'purchase_price.required' => 'Purchase price is required.',
            'purchase_price.numeric' => 'Purchase price must be a valid number.',
            'purchase_price.min' => 'Purchase price cannot be negative.',

            // sell_price
            'sell_price.required' => 'Selling price is required.',
            'sell_price.numeric' => 'Selling price must be a valid number.',
            'sell_price.min' => 'Selling price cannot be negative.',
            'sell_price.gte' => 'Selling price must be greater than or equal to the purchase price.',  // Updated message for 'gte' rule

            // expiration_date
            // 'expiration_date.date' => 'Expiration date must be a valid date.',
            // 'expiration_date.after' => 'Expiration date must be a date after today.',

            // alert_date
            // 'alert_date.date' => 'Alert date must be a valid date.',
            // 'alert_date.before' => 'Alert date must be before the expiration date.',
        ];
    }

}

