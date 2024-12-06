<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
	protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'image',
    ];
    public function stockIns()
    {
        return $this->hasMany(StockIn::class);
    }

    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class);
    }
}
