<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'store_id', // Added store_id
        'debit',
        'credit',
        'balance',
        'note',
    ];

    // Define a relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define a relationship with the Store model (if applicable)
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
