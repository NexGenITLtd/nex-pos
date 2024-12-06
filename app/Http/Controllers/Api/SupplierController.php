<?php
namespace App\Http\Controllers\Api;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function supplier_by_id($id){
        return $product = Supplier::where('id',$id)->get();
    }
    public function suppliers(){
        return $suppliers = Supplier::all();
    }
}
