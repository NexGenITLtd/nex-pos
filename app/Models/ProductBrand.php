<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProductBrand extends Model
{
    use HasRoles,HasFactory;

    protected $fillable = ['name'];
}
