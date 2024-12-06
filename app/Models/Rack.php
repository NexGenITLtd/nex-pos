<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $fillable = [
        // Add other fields here
        'name',
        'store_id',
    ];
    public function store(){
        return $this->belongsTo(Store::class);
    }
}
