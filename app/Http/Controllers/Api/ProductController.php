<?php
namespace App\Http\Controllers\Api;
use App\models\Product;
use App\models\StockIn;
use App\models\SellProduct;
use App\models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class ProductController extends Controller
{
  public function product_by_id($id){
      return $product = Product::with('stockIns')->where('id',$id)->get();
  }
  public function products(){
      return $products = Product::all();
  }
}
