<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;
        // Define the table name if it's not the default 'daily_reports'
    protected $table = 'daily_reports';

    // Define the fillable attributes
    protected $fillable = [
        'store_id',
        'date',
        'total_invoices',
        'previous_cash_in_hand',
        'extra_cash',
        'total_sales',
        'total_return_sell',
        'total_purchase_price',
        'total_profit',
        'net_profit',
        'total_due',
        'total_supplier_payment',
        'total_expense',
        'total_salary',
        'extra_expense',
        'owner_deposit',
        'bank_deposit',
        'cash_in_hand',
    ];

    // Define any relationships (if applicable)
    public function store()
    {
        return $this->belongsTo(Store::class); // Assuming you have a Store model
    }
}
