<?php

// app/Models/SupplierPaymentAlert.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPaymentAlert extends Model
{
    use HasFactory;

    // Add 'store_id' to the fillable property
    protected $fillable = [
        'supplier_id', // existing fields
        'store_id',     // new field added
        'amount',
        'pay_date',
        'notice_date',
    ];

    // Define relationships if not already defined
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
