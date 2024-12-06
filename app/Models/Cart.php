<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'product_id',
        'user_id',
        'store_id',
        'purchase_price',
        'sell_price',
        'qty',
        'discount',
        'vat',
        'status',
    ];
}
