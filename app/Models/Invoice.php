<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }

    public function sellProducts()
    {
        return $this->hasMany(SellProduct::class);
    }
    public function returnSellProducts()
    {
        return $this->hasMany(ReturnSellProduct::class);
    }

    // New relationships for sell_person and manager
    public function sell_person()
    {
        return $this->belongsTo(User::class, 'sell_person_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id'); // foreign key: 'store_id'
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id'); // foreign key: 'customer_id'
    }

}
