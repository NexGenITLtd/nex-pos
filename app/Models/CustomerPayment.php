<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
	protected $fillable = [
        'invoice_id',
        'payment_type',
        'bank_account_id',
        'payment_from_account_no',
        'payment_trx_note',
        'amount',
    ];
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id'); // foreign key: 'invoice_id'
    }

}
