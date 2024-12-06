<?php

namespace App\Http\Controllers\Api;

use App\Models\Rack;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function racks($store_id = null)
	{
	    return $store_id ? Rack::where('store_id', $store_id)->get() : Rack::all();
	}

}
