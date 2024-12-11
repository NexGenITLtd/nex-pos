<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'store_id',
        'bank_name',
        'account_no',
        'account_type',
        'initial_balance',
        'current_balance'
    ];

    // Define the relationship to the Store model
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Define the relationship to the Transaction model
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // You can add a method to update the current balance
    public function updateBalance($debit, $credit)
    {
        $this->current_balance = $this->current_balance - $debit + $credit;
        $this->save();
    }

}
