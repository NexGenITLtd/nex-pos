<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = [
        'invoice_no',
        'store_id',
        'stock_date',
    ];
    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function stock_ins(){
        return $this->hasMany(StockIn::class);
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id'); // foreign key: 'supplier_id'
    }

}
