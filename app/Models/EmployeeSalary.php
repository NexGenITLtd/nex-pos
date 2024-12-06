<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function bank_account(){
        return $this->belongsTo(BankAccount::class);
    }
}
