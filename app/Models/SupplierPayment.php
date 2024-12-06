<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
	protected $fillable = ['store_id', 'supplier_id', 'bank_account_id', 'amount', 'note', 'paid_date'];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function bank_account(){
        return $this->belongsTo(BankAccount::class);
    }
    public function store(){
        return $this->belongsTo(Store::class);
    }
    
}
