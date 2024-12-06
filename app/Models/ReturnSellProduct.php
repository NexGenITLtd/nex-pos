<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnSellProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'store_id',
        'product_id',
        'product_name',
        'purchase_price',
        'sell_price',
        'qty',
        'vat',
        'discount',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
