<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductBrand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function brands(){
        return $brands = ProductBrand::all();
    }
}
