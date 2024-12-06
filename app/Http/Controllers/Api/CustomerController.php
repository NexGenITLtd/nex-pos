<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function getCustomer(Request $request){
        return $customer = Customer::where('phone', $request->phone)->first();
    }
}
