<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'date',
        'bank_account_id',
        'amount',
        'transaction_type',  // Include transaction_type
        'note',
    ];

    // Optional: You can add a helper to check if it's a deposit or withdrawal
    public function isDeposit()
    {
        return $this->transaction_type === 'deposit';
    }

    public function isWithdrawal()
    {
        return $this->transaction_type === 'withdrawal';
    }
}
