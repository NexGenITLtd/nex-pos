<?php
namespace App\Http\Controllers\Backend;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class CartController extends Controller
{
  public function add_to_cart(Request $request)
  {
      // Retrieve the product along with sellProducts and stockIns filtered by store_id
      $product = Product::where('status', 'active')
      ->with([
          'sellProducts' => function ($query) {
              $query->where('store_id', Auth::user()->store_id);
          },
          'returnSellProducts' => function ($query) {
              $query->where('store_id', Auth::user()->store_id);
          },
          'stockIns' => function ($query) {
              $query->where('store_id', Auth::user()->store_id)
                      ->orderBy('id', 'desc');
          }
      ])->find($request->id);

      if (!$product) {
          return 'product_not_found';
      }

      // Calculate the total stock and sold products based on the store
      $total_stock = $product->stockIns->sum('qty');
      $total_sell_product = $product->sellProducts->sum('qty');
      $total_return_sell_product = $product->returnSellProducts->sum('qty');
      $total_cart_product = Cart::where('product_id', $product->id)
                              ->where('store_id', Auth::user()->store_id)
                              ->sum('qty');

      $available_stock = ($total_stock + $total_return_sell_product) - ($total_sell_product + $total_cart_product);

      if ($available_stock <= 0 && $request->status=='') {
          return 'insufficient';
      }

      // Check if the product is already in the cart for the user
      $cart = Cart::where("product_id", $request->id)
                  ->where("user_id", Auth::user()->id)
                  ->where("store_id", Auth::user()->store_id)
                  ->first();

      if ($cart) {
          // Update the quantity if already in cart
          $cart->qty = ($cart->qty + 1);
          $cart->save();
      } else {
          // Get the latest stock entry for the product based on the store
          $stock_in = StockIn::where("product_id", $request->id)
                             ->where("store_id", Auth::user()->store_id)
                             ->orderBy('id', 'desc')
                             ->first();

          // Add the product to the cart
          $new_cart = new Cart;
          $new_cart->product_id = $product->id;
          $new_cart->status = $request->status;
          $new_cart->purchase_price = $stock_in->purchase_price;
          $new_cart->sell_price = $stock_in->sell_price;
          $new_cart->product_name = $product->name;
          $new_cart->user_id = Auth::user()->id;
          $new_cart->store_id = Auth::user()->store_id;
          $new_cart->qty = 1;
          $new_cart->save();
      }
  }
  public function cart_list()
  {
    // Retrieve the user's cart for the store
    $carts = Cart::where("store_id", Auth::user()->store_id)
                  ->where("user_id", Auth::user()->id)
                  ->get();
    return $carts;
  }

  public function update_to_cart(Request $request)
  {
      if ($request->isMethod('post')) {
          // Retrieve the cart entry for the specific user and store
          $cart = Cart::where("store_id", Auth::user()->store_id)
                      ->where("user_id", Auth::user()->id)
                      ->where('id', $request->id)
                      ->first();

          if (!$cart) {
              return response()->json(['error' => 'Cart not found'], 404);
          }

          if ($request->field == 'qty') {
              // Retrieve the product along with sellProducts and stockIns filtered by store_id
              $product = Product::with([
                  'sellProducts' => function ($query) {
                      $query->where('store_id', Auth::user()->store_id);
                  },
                  'returnSellProducts' => function ($query) {
                      $query->where('store_id', Auth::user()->store_id);
                  },
                  'stockIns' => function ($query) {
                      $query->where('store_id', Auth::user()->store_id);
                  }
              ])->find($cart->product_id);

              // Calculate the total stock and sold products for the store
              $total_stock = $product->stockIns->sum('qty');
              $total_sell_product = $product->sellProducts->sum('qty');
              $total_return_sell_product = $product->returnSellProducts->sum('qty');
              $total_cart_product = $request->value; // Updated quantity from request

              $available_stock = ($total_stock + $total_return_sell_product) - ($total_sell_product + $total_cart_product);

              // Check if the requested quantity exceeds available stock
              if ($available_stock < 0) {
                  return 'insufficient';
              }
          }

          // Validate and cast the request input
          $field = $request->field;
          $value = $request->value;

          // Ensure that if the field is `qty`, the value is numeric
          if ($field === 'qty') {
              // Cast or validate the value to ensure it's numeric
              $value = is_numeric($value) ? (float) $value : 0; // Or throw an error if not valid
          }

          // Dynamically update the field in the cart
          $cart->{$field} = $value;

          // Save the updated cart
          $cart->save();
      }
  }

  public function remove_to_cart($id)
  {
    if($id) {
      $cart = Cart::where("id", $id)->first();
      if(isset($cart)) {
        $cart->delete();
      }
      session()->flash('success', 'Product removed successfully');
    }
  }
}
