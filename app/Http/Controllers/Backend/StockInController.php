<?php

namespace App\Http\Controllers\Backend;

use Auth;
use Image;
use App\Models\Rack;
use App\Models\Product;
use App\Models\Batch;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\ProductBrand;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class StockInController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view stock in')->only('index','show');
        $this->middleware('permission:create stock in')->only('create', 'store');
        $this->middleware('permission:update stock in')->only('updateStock', 'addStockModify');
        $this->middleware('permission:delete stock in')->only('destroy','deleteStock');
    }
    public function index(){
        $batchs = Batch::with('store')->latest()->paginate(100);
        return view("products.stockins.index")->with(compact('batchs'));
    }

    public function create()
    {
        $products = Product::orderBy('id', 'desc')->get();
        $suppliers = Supplier::orderBy('id')->get();
        return view("products.stockins.create")->with(compact('products', 'suppliers'));
    }

    public function store(Request $request)
	{
	    if ($request->isMethod('post')) {

	        // Add validation rules
	        $request->validate([
	            'batch_id' => 'required|integer',
	            'invoice_no' => 'required|string|max:255',
	            'store_id' => 'required|integer',
	            'stock_date' => 'required|date',
	            'product_id.*' => 'required|integer',
	            'supplier_id.*' => 'required|integer',
	            'purchase_price.*' => 'required|numeric|min:0',
	            'sell_price.*' => 'required|numeric|min:0',
	            'rack_id.*' => 'nullable|integer',
	            'qty.*' => 'required|numeric|min:1',
	            'expiration_date.*' => 'nullable|date',
	            'alert_date.*' => 'nullable|date',
	        ]);

	        // Check if the batch already exists
	        $existingBatch = Batch::find($request->batch_id);

	        if ($existingBatch) {
	            // If the batch exists, create a new one with a different ID
	            $batch = new Batch;
	            $batch->invoice_no = $request->invoice_no;
	            $batch->store_id = $request->store_id;
	            $batch->stock_date = $request->stock_date;
	            $batch->save();
	        } else {
	            // If the batch does not exist, create it with the provided ID
	            $batch = new Batch;
	            $batch->id = $request->batch_id;
	            $batch->invoice_no = $request->invoice_no;
	            $batch->store_id = $request->store_id;
	            $batch->stock_date = $request->stock_date;
	            $batch->save();
	        }

	        // Get the size of products array
	        $size = count(collect($request)->get('product_id'));

	        // Loop through each product and save it in the stock_in table
	        for ($i = 0; $i < $size; $i++) {
	            $stock_in = new StockIn;
	            $stock_in->product_id = $request->get('product_id')[$i];
	            $stock_in->supplier_id = $request->get('supplier_id')[$i];
	            $stock_in->purchase_price = $request->get('purchase_price')[$i];
	            $stock_in->sell_price = $request->get('sell_price')[$i];
	            $stock_in->rack_id = $request->get('rack_id')[$i];
	            $stock_in->qty = $request->get('qty')[$i];
	            $stock_in->expiration_date = $request->get('expiration_date')[$i];
	            $stock_in->alert_date = $request->get('alert_date')[$i];
	            $stock_in->batch_id = $batch->id;
	            $stock_in->store_id = $request->store_id;
	            $stock_in->save();
	        }

	        // Redirect with success message
	        return redirect()->route('stockins.create')->with('flash_success', '
	            <script>
	            Toast.fire({
	              icon: `success`,
	              title: `Product stock in successfully added`
	            })
	            </script>
	        ');
	    }
	}
    
    public function show($id){
        $batch = Batch::with('store','stock_ins')->where('id', $id)->first();
        $suppliers = Supplier::all();
        $products = Product::all();
        $racks = Rack::all();
        return view("products.stockins.show")->with(compact('batch','suppliers','products','racks'));
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            try {
                // Check if the batch exists
                $batch = Batch::findOrFail($id);

                // Delete associated StockIn records
                StockIn::where('batch_id', $id)->delete();

                // Delete the batch
                $batch->delete();

                // Return success message
                return redirect()->route('stockins.index')->with('flash_success', '
                    <script>
                        Toast.fire({
                            icon: "success",
                            title: "Stock and associated records successfully deleted"
                        });
                    </script>
                ');
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                // Handle case where batch does not exist
                return redirect()->route('stockins.index')->with('flash_success', '
                    <script>
                        Toast.fire({
                            icon: "error",
                            title: "Batch not found"
                        });
                    </script>
                ');
            } catch (\Exception $e) {
                // Log unexpected exceptions and display an error
                \Log::error('Error deleting batch: ' . $e->getMessage());
                return redirect()->route('stockins.index')->with('flash_success', '
                    <script>
                        Toast.fire({
                            icon: "error",
                            title: "An unexpected error occurred. Please try again."
                        });
                    </script>
                ');
            }
        } else {
            // Handle invalid or empty ID
            return redirect()->route('stockins.index')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: "error",
                        title: "Invalid batch ID"
                    });
                </script>
            ');
        }
    }

    public function updateStock(Request $request)
    {
        $stockIn = StockIn::find($request->id);
        if ($stockIn) {
            $fields = $request->fields;

            // Update fields dynamically based on what was sent
            foreach ($fields as $field => $value) {
                $stockIn->$field = $value;
            }

            $stockIn->save();

            return response()->json(['message' => 'Stock updated successfully!'], 200);
        } else {
            return response()->json(['message' => 'Stock not found!'], 404);
        }
    }

    public function deleteStock(Request $request)
    {
        $stockIn = StockIn::find($request->id);

        if ($stockIn) {
            $stockIn->delete(); // Delete the stock entry
            return response()->json(['message' => 'Stock entry deleted successfully']);
        } else {
            return response()->json(['error' => 'Stock entry not found'], 404);
        }
    }

    public function addStockModify(Request $request)
    {
        $validatedData = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'qty' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'expiration_date' => 'nullable|date',
            'alert_date' => 'nullable|date',
            'rack_id' => 'nullable|exists:racks,id',
        ]);
        $batch = Batch::find($validatedData['batch_id']);
        $newStock = StockIn::create([
            'store_id' => $batch->store_id,
            'batch_id' => $validatedData['batch_id'],
            'product_id' => $validatedData['product_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'qty' => $validatedData['qty'],
            'purchase_price' => $validatedData['purchase_price'],
            'sell_price' => $validatedData['sell_price'],
            'expiration_date' => $validatedData['expiration_date'],
            'alert_date' => $validatedData['alert_date'],
            'rack_id' => $validatedData['rack_id']
        ]);

        return response()->json(['message' => 'Stock entry added successfully', 'new_stock' => $newStock]);
    }


}
