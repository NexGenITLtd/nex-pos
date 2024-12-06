<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function invoices()
	{
	    return $this->hasMany(Invoice::class);
	}
	protected $fillable = [
        'name',
        'phone',
        'discount',
        'email',
        'address',
        'img',
        'membership', // Include this if 'membership' is also intended to be fillable.
    ];
}
