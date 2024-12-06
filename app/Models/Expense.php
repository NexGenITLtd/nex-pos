<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function bank_account(){
        return $this->belongsTo(BankAccount::class);
    }
}
