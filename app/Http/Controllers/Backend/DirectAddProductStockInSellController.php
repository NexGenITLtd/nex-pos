<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectStockInRequest;
use App\Http\Requests\StockInDirectSellRequest;
use Illuminate\Support\Str;
use App\Models\Batch;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\Cart;
use App\Models\Store;
use App\Models\ProductBrand;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Auth;

class DirectAddProductStockInSellController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view direct stock in')->only('index','show');
        $this->middleware('permission:create direct stock in')->only('create', 'store', 'storeDirect');
    }
    public function create()
    {
        $data = [
            'stores' => Store::all(),
            'brands' => ProductBrand::all(),
            'suppliers' => Supplier::all(),
            'units' => Unit::all()
        ];

        return view('products.create-direct-stock-in', $data);
    }

    public function storeDirect(DirectStockInRequest $request)
    {
        return $this->storeStockIn($request, 'direct-stock');
    }

    public function store(StockInDirectSellRequest $request)
    {
        return $this->storeStockIn($request, 'direct-sell');
    }

    private function storeStockIn($request, $type)
    {
        \DB::beginTransaction();

        try {
            // Create batch
            $batch = $this->createBatch($request->store_id, $type);

            // Create product
            $product = $this->createProduct($request);

            // Create stock-in record
            $this->createStockIn($batch, $product, $request);

            // Optionally, create a cart for direct sell
            if ($type === 'direct-sell') {
                $this->createCart($product, $request);
            }

            \DB::commit();
            return response()->json(['message' => 'Row saved successfully!'], 200);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Failed to save row. Please try again.', 'error' => $e->getMessage()], 500);
        }
    }

    private function createBatch($storeId, $type)
    {
        return Batch::create([
            'store_id' => $storeId,
            'invoice_no' => $this->generateInvoiceNo($storeId, $type),
            'stock_date' => now(),
        ]);
    }

    private function createProduct($request)
    {
        return Product::firstOrCreate(
            ['name' => $request->product_name],
            [
                'product_category_id' => $request->product_category_id ?? 0,
                'product_sub_category_id' => $request->product_sub_category_id ?? 0,
                'brand_id' => $request->brand_id ?? 0,
                'unit' => $request->unit_id ?? 'pcs', // Default to 'pcs'
            ]
        );
    }

    private function createStockIn($batch, $product, $request)
    {
        StockIn::create([
            'store_id' => $request->store_id,
            'batch_id' => $batch->id,
            'product_id' => $product->id,
            'supplier_id' => $request->supplier_id ?? 0,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'qty' => $request->qty ?? 1,
            'rack_id' => $request->rack_id ?? 0,
            'expiration_date' => $request->expiration_date ?? null,
            'alert_date' => $request->alert_date ?? null,
        ]);
    }

    private function createCart($product, $request)
    {
        Cart::create([
            'product_name' => $product->name,
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'store_id' => $request->store_id,
            'purchase_price' => $request->purchase_price,
            'sell_price' => $request->sell_price,
            'qty' => $request->qty ?? 1,
            'discount' => 0,
            'vat' => 0,
        ]);
    }

    private function generateInvoiceNo($storeId, $type)
    {
        $store = Store::find($storeId);
        if ($store) {
            return $store->name . ' ' . ucfirst($type) . ': ' . now();
        }

        return strtoupper(Str::random(10)) . '-' . now()->timestamp;
    }
}
