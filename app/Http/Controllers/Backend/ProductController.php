<?php

namespace App\Http\Controllers\Backend;

use Auth;
use App\Models\Product;
use App\Models\Store;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\ReturnSellProduct;
use App\Models\ProductBrand;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view product', ['only' => ['index','oneLineBarcode','multiLineBarcode']]);
        $this->middleware('permission:create product', ['only' => ['create','store']]);
        $this->middleware('permission:update product', ['only' => ['update','edit','toggleStatus']]);
        $this->middleware('permission:delete product', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $storeId = $request->input('store_id', Auth::user()->store_id);
        $cardHeader = $storeId ? ('Products for ' . (Store::find($storeId)->name ?? 'Unknown Store')) : 'All Products';

        $products = Product::with(['category', 'subcategory', 'brand', 'stockIns', 'sellProducts', 'returnSellProducts'])
            ->when($storeId, function ($query) use ($storeId) {
                $query->whereHas('stockIns', fn($q) => $q->where('store_id', $storeId))
                      ->orWhereHas('sellProducts', fn($q) => $q->where('store_id', $storeId))
                      ->orWhereHas('returnSellProducts', fn($q) => $q->where('store_id', $storeId));
            })
            ->latest()
            ->paginate(500);

        $stores = Store::all();

        return view('products.index', compact('products', 'stores', 'cardHeader'));
    }

    public function create()
    {
        return view('products.create', ['brands' => ProductBrand::all()]);
    }

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->route('products.create')->with('flash_success', '<script>toastr.success("Product created successfully!")</script>');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        return redirect()->route('products.edit', $product->id)->with('flash_success', '<script>toastr.success("Product updated successfully!")</script>');
    }

    public function destroy(Product $product)
    {
        try {
            $product->stockIns()->delete();
            $product->sellProducts()->delete();
            $product->returnSellProducts()->delete();
            $product->delete();

            return redirect()->route('products.index')->with('flash_success', '<script>toastr.success("Product deleted successfully!")</script>');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('flash_success', '<script>toastr.error("An error occurred during deletion.")</script>');
        }
    }

    public function toggleStatus(Request $request, Product $product)
    {
        $request->validate(['status' => 'required|in:active,inactive']);
        $product->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    // public function generateBarcode(Request $request, $id, $type = 'multi-line')
    // {
        
    //     $product = Product::FindOrFail($id);
    //     $generator = new BarcodeGeneratorPNG();
    //     // Generate barcode as base64 encoded image
    //     $barcode = $generator->getBarcode($product->id, $generator::TYPE_CODE_128);
    //     $barcodeBase64 = base64_encode($barcode);
    //     // Store barcode in product object (or as a separate array)
    //     $product->barcodeBase64 = $barcodeBase64;
    //     $view = $type === 'multi-line' ? 'products.barcodes.multi-line' : 'products.barcodes.one-line';
    //     return view($view, compact('product'));
    // }




    public function multiLineBarcode(Request $request, $id){
        $product = Product::FindOrFail($id);

        $generator = new BarcodeGeneratorPNG();

        // Generate barcode as base64 encoded image
        $barcode = $generator->getBarcode($product->id, $generator::TYPE_CODE_128);
        $barcodeBase64 = base64_encode($barcode);
        // Store barcode in product object (or as a separate array)
        $product->barcodeBase64 = $barcodeBase64;
        
        return view("products.barcodes.multi-line")->with(compact('product'));
    }
    public function oneLineBarcode(Request $request, $id)
    {
        $product = Product::FindOrFail($id);

        $generator = new BarcodeGeneratorPNG();

        // Generate barcode as base64 encoded image
        $barcode = $generator->getBarcode($product->id, $generator::TYPE_CODE_128);
        $barcodeBase64 = base64_encode($barcode);
        // Store barcode in product object (or as a separate array)
        $product->barcodeBase64 = $barcodeBase64;
        
        return view("products.barcodes.one-line")->with(compact('product'));
    }
}
