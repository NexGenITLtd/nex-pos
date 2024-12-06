<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    use HasFactory;

    // Define the fillable fields
    protected $fillable = [
        'name',
        'phone',
        'email',
        'logo',
        'print_logo',
        'fav_icon',
        'short_about',
        'address',
        'currency',
        'map_embed',
        'return_policy',
        'barcode_height',
        'barcode_width',
        'user_id',
    ];
}

