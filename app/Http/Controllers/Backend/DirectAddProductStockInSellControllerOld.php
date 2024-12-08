<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Cart;
use App\Models\Rack;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Store;
use App\Models\ProductBrand;
use App\Http\Requests\DirectStockInRequest;
use App\Http\Requests\StockInDirectSellRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Auth;

class DirectAddProductStockInSellController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view cart')->only('index');
        $this->middleware('permission:create cart')->only(['create', 'store', 'storeDirect']);
        $this->middleware('permission:update cart')->only(['edit', 'update']);
        $this->middleware('permission:delete cart')->only('destroy');
    }
	public function create()
	{
		$stores = Store::get();
		$brands = ProductBrand::get();
		$suppliers = Supplier::get();
		$units = Unit::get();
		return view('products.create-direct-stock-in')->with(compact('stores','brands','suppliers','units'));
	}
	public function storeDirect(DirectStockInRequest $request)
    {
        \DB::beginTransaction();

        try {
            // Step 1: Create or fetch the batch record
            $batch = Batch::create([
                'store_id' => $request->store_id,
                'invoice_no' => $this->generateInvoiceNoForDirectStock($request->store_id),
                'stock_date' => now(),
            ]);

            // Step 2: Create or fetch the product
            $product = Product::firstOrCreate(
                ['name' => $request->product_name], // Search condition
                [
                    'product_category_id' => $request->product_category_id,
                    'product_sub_category_id' => $request->product_sub_category_id ?? 0,
                    'brand_id' => $request->brand_id ?? 0,
                    'unit' => $request->unit_id ?? null,  // Defaulting to 0 if not provided
                ]
            );

            // Step 3: Insert a stock-in record
            StockIn::create([
                'store_id' => $request->store_id,
                'batch_id' => $batch->id,
                'product_id' => $product->id,
                'supplier_id' => $request->supplier_id ?? 0,
                'purchase_price' => $request->purchase_price,
                'sell_price' => $request->sell_price,
                'qty' => $request->qty,
                'rack_id' => $request->rack_id ?? 0,
                'expiration_date' => $request->expiration_date,
                'alert_date' => $request->alert_date,
            ]);

            \DB::commit();

            return response()->json(['message' => 'Row saved successfully!'], 200);
        } catch (\Exception $e) {
            \DB::rollback();

            \Log::error('StockIn Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to save row. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
	public function store(StockInDirectSellRequest $request)
	{
	    // The request is already validated
	    $validated = $request->validated();

	    \DB::beginTransaction();

	    try {
	        // Step 1: Create or fetch a batch
	        $batch = Batch::create([
	            'store_id' => Auth::user()->store_id,
	            'invoice_no' => $this->generateInvoiceNoForDirectSell(),
	            'stock_date' => now(),
	        ]);

	        // Step 2: Create or fetch the product
	        $product = Product::create([
	            'name' => $validated['product_name'],
	            'product_category_id' => 0,
	            'product_sub_category_id' => 0,
	            'brand_id' => 0,
	            'unit' => 'pcs',
	        ]);

	        // Step 3: Insert stock-in record
	        $stockIn = StockIn::create([
	            'store_id' => Auth::user()->store_id,
	            'batch_id' => $batch->id,
	            'product_id' => $product->id,
	            'supplier_id' => $validated['supplier_id'],
	            'purchase_price' => $validated['purchase_price'],
	            'sell_price' => $validated['sell_price'],
	            'qty' => $validated['qty'],
	            'rack_id' => $validated['rack_id'],
	            'expiration_date' => $validated['expiration_date'],
	            'alert_date' => $validated['expiration_date'] 
	                ? \Carbon\Carbon::parse($validated['expiration_date'])->subMonth(2) 
	                : null,
	        ]);

	        // Step 4: Add the product to the cart
	        Cart::create([
	            'product_name' => $product->name,
	            'product_id' => $product->id,
	            'user_id' => auth()->id(),
	            'store_id' => Auth::user()->store_id,
	            'purchase_price' => $validated['purchase_price'],
	            'sell_price' => $validated['sell_price'],
	            'qty' => $validated['qty'],
	            'discount' => 0,
	            'vat' => 0,
	        ]);

	        \DB::commit();

	        return response()->json(['message' => 'Row saved successfully!'], 200);

	    } catch (\Exception $e) {
	        \DB::rollback();
	        return response()->json(['message' => 'Failed to save row. Please try again.', 'error' => $e->getMessage()], 500);
	    }
	}

	private function generateInvoiceNoForDirectSell()
	{
	    return Auth::user()->store->name.' Direct Stock Sell: '.now();
	}
	
	private function generateInvoiceNoForDirectStock($storeId)
	{
	    // Retrieve the store ID from the request
	    $storeId = $storeId;

	    // Try to get the store using the provided store ID
	    $store = Store::find($storeId);

	    if ($store) {
	        // Generate the invoice number using the store name and current timestamp
	        return $store->name . ' Direct Stock: ' . now();
	    } else {
	        // If store doesn't exist, generate a random unique invoice number
	        return 'Direct Stock: ' . strtoupper(str_random(10)) . '-' . now()->timestamp;
	    }
	}
}
