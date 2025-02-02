<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BankAccount;
use App\Models\Store;
use App\Models\User;

class Transaction extends Model
{
    protected $fillable = [
        'store_id',
        'bank_account_id',
        'debit',
        'credit',
        'balance',
        'created_by',
        'note',
    ];

    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function bankAccount(){
        return $this->belongsTo(BankAccount::class);
    }
    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function createTransaction($store_id, $bank_account_id, $user_id, $debit = 0, $credit = 0, $note = null)
    {
        // Fetch the bank account
        $bankAccount = BankAccount::find($bank_account_id);

        if (!$bankAccount) {
            throw new \Exception("Bank account with ID {$bank_account_id} not found.");
        }

        // Calculate the new balance
        $newBalance = $bankAccount->current_balance - $debit + $credit;

        // Create the transaction
        $transaction = self::create([
            'store_id' => $store_id,
            'bank_account_id' => $bank_account_id,
            'debit' => $debit,
            'credit' => $credit,
            'balance' => $newBalance,
            'created_by' => $user_id,
            'note' => $note,
        ]);

        // Update the bank account's balance
        $bankAccount->updateBalance($debit, $credit);

        return $transaction;
    }

}
