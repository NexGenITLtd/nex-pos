<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    // Define the relationship to the invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
