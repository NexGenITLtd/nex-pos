<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Rack;
use App\Models\Invoice;
use App\Models\SellProduct;
use App\Models\ReturnSellProduct;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerPayment;
use App\Models\Cart;
use App\Models\StockIn;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Collection;

use Auth;

class InvoiceController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view invoice')->only('index','show');
        $this->middleware('permission:create invoice')->only('create', 'store');
        $this->middleware('permission:update invoice')->only('edit', 'update','sellProductUpdate','sellProductUpdateQty','sellProductDelete');
        $this->middleware('permission:delete invoice')->only('destroy');
    }
    public function create(Request $request){
  
        $store_id = Auth::user()->store_id;
        
	    $products = Product::where('status', 'active')
	    ->with([
	        'stockIns' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        },
	        'sellProducts' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        },
	        'returnSellProducts' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        }
	    ])->get()->filter(function ($product) {
	        $availableQty = ($product->stockIns->sum('qty') + $product->returnSellProducts->sum('qty')) - $product->sellProducts->sum('qty');
	        return $availableQty > 0;
	    });

	    $suppliers = Supplier::get();
	    $racks = Rack::where('store_id', Auth::user()->store_id)->get();
        return view('invoices.create')->with(compact(
        	'products',
        	'suppliers',
        	'racks',

        ));
    }
    public function store(Request $request){
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:15',
                'total_payable_amount' => 'required|numeric',
                'total_payments' => 'required|numeric',
                'carts' => 'required|array',
            ]);

            $invoice = null;

            DB::transaction(function() use ($request, &$invoice) {
                // Customer Creation
                if ($request->customer_id == '' || $request->customer_id == 0) {
                    $customer = new Customer;
                    $customer->name = $request->name;
                    $customer->phone = $request->phone;
                    if($request->make_member=='Member'){
			        	$customer->discount = $request->discount;
			        	$customer->membership = $request->make_member;
			        }
                    $customer->save();
                    $customer_id = $customer->id;
                } else {
                    $customer_id = $request->customer_id;
                    $customer = Customer::find($customer_id);
                    $customer->name = $request->name;
                    $customer->phone = $request->phone;
                    if($request->make_member=='Member'){
			        	$customer->discount = $request->discount;
			        	$customer->membership = $request->make_member;
			        }
                    $customer->save();
                }

                // Invoice Creation
                $due = ($request->total_payable_amount - $request->total_payments);
                $invoice = new Invoice;
                $invoice->customer_id = $customer_id;
                $invoice->total_bill = $request->total_payable_amount;
                $invoice->product_return = $request->product_return;
                $invoice->paid_amount = $request->total_payments;
                $invoice->due_amount = ($due > 0) ? $due : 0;
                $invoice->discount = $request->discount;
                $invoice->less_amount = $request->less;
                $invoice->manager_id = Auth::user()->id;
                $invoice->sell_person_id = $request->sale_person_id;
                $invoice->store_id = Auth::user()->store_id;
                $invoice->save();

                // Process Cart Items (SellProduct)
                if (count($request->carts) > 0) {
                    foreach ($request->carts as $cart) {
                    	if($cart['status']=='Return'){
                    		$return_sell_product = new ReturnSellProduct;
	                        $return_sell_product->invoice_id = $invoice->id;
	                        $return_sell_product->store_id = Auth::user()->store_id;
	                        $return_sell_product->product_id = $cart['product_id'];
	                        $return_sell_product->product_name = $cart['product_name'];
	                        $return_sell_product->purchase_price = $cart['purchase_price'];
	                        $return_sell_product->sell_price = $cart['sell_price'];
	                        $return_sell_product->qty = $cart['qty'];
	                        $return_sell_product->vat = $cart['vat'];
	                        $return_sell_product->discount = $cart['discount'];
	                        $return_sell_product->save();
                    	}else{
                    		$sell_product = new SellProduct;
	                        $sell_product->invoice_id = $invoice->id;
	                        $sell_product->store_id = Auth::user()->store_id;
	                        $sell_product->product_id = $cart['product_id'];
	                        $sell_product->product_name = $cart['product_name'];
	                        $sell_product->purchase_price = $cart['purchase_price'];
	                        $sell_product->sell_price = $cart['sell_price'];
	                        $sell_product->qty = $cart['qty'];
	                        $sell_product->vat = $cart['vat'];
	                        $sell_product->discount = $cart['discount'];
	                        $sell_product->save();
                    	}
                    }

                    // Delete all processed cart items at once (batch delete)
                    Cart::whereIn('id', array_column($request->carts, 'id'))->delete();
                }

                // Process Payments
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'cash_payment', $request->cash_payment, $request->cash_account_no_id);
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'card_payment', $request->card_payment, $request->card_account_no_id, $request->card_number, $request->card_type);
                $this->processPayment(Auth::user()->store_id, $invoice->id, 'mobile_payment', $request->mobile_payment, $request->mobile_account_no_id, $request->sender_no, $request->trx_no);
            });

			return $invoice->id;
        }
        
    }
    
    private function processPayment($store_id, $invoice_id, $payment_type, $amount, $account_id, $account_no = null, $trx_note = null)
    {
        if ($amount > 0) {
            $customer_payment = new CustomerPayment;
            $customer_payment->store_id = $store_id;
            $customer_payment->invoice_id = $invoice_id;
            $customer_payment->payment_type = $payment_type;
            $customer_payment->amount = $amount;
            $customer_payment->bank_account_id = $account_id;
            $customer_payment->payment_from_account_no = $account_no;
            $customer_payment->payment_trx_note = $trx_note;
            $customer_payment->save();
        }
    }

	public function edit($id)
	{
	    // Retrieve the invoice using the given ID
	    $invoice = Invoice::find($id);

	    // Ensure the invoice exists
	    if (!$invoice) {
	        return redirect()->back()->with('error', 'Invoice not found.');
	    }

	    $store_id = $invoice->store_id;

	    // Check if the invoice is within the 10-day window
	    $invoiceAge = Carbon::parse($invoice->created_at)->diffInDays(Carbon::now());
	    if ($invoiceAge > 10) {
	        return response()->json(['error' => 'Invoice cannot be edited after 10 days'], 403);
	    }

		// Retrieve related sell products for the invoice
		$sellProducts = SellProduct::where('invoice_id', $invoice->id)->get();

		// Add a status field to sell products
		$sellProducts->transform(function ($item) {
		    $item->status = ''; // Set the status to an empty string
		    return $item;
		});

		// Retrieve related return sell products for the invoice
		$returnSellProducts = ReturnSellProduct::where('invoice_id', $invoice->id)->get();

		// Add a status field to return sell products
		$returnSellProducts->transform(function ($item) {
		    $item->status = 'Return'; // Set the status to 'Return'
		    return $item;
		});

		$mergedProducts = $sellProducts->merge($returnSellProducts);

		$mergedProducts = $mergedProducts->sortBy('product_name')->values(); 
		$mergedProductsArray = $mergedProducts->toArray();

		// Output or process the merged collection
		$mergedProducts;



	    // Retrieve customer payments related to this invoice
	    $payments = CustomerPayment::where('invoice_id', $invoice->id)
	                               ->get();

	    // Retrieve any related data (customers, stores, etc.)
	    $customers = Customer::all();

	    $products = Product::with([
	        'stockIns' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        },
	        'sellProducts' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        },
	        'returnSellProducts' => function ($query) use ($store_id) {
	            $query->where('store_id', $store_id);
	        }
	    ])->get()->filter(function ($product) {
	        // Calculate the available quantity for each product
	        $availableQty = ($product->stockIns->sum('qty') + $product->returnSellProducts->sum('qty')) - $product->sellProducts->sum('qty');
	        return $availableQty > 0;
	    });

	    // Pass data to the view
	    return view('invoices.edit', compact('invoice', 'sellProducts', 'returnSellProducts', 'products', 'customers', 'payments','mergedProducts'));
	}


	public function sellProductUpdate(Request $request)
	{
	    $product = Product::with([
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
	    $total_return_sell_product = $product->sellProducts->sum('qty');
	    $total_cart_product = Cart::where('product_id', $product->id)
	                              ->where('store_id', Auth::user()->store_id)  // Filter by store_id
	                              ->sum('qty');

	    $available_stock = ($total_stock + $total_return_sell_product) - ($total_sell_product + $total_cart_product);

	    if ($available_stock <= 0) {
	        return 'insufficient';
	    }

	    // Check if the sell product exists for the given invoice and store
	    $sell_product = SellProduct::where("product_id", $request->id)
	                               ->where("invoice_id", $request->invoice_id)
	                               ->where("store_id", Auth::user()->store_id)  // Filter by store_id
	                               ->first();

	    if ($sell_product) {
	        // Update the quantity if already in sell products
	        $sell_product->qty = ($sell_product->qty + 1);
	        $sell_product->save();
	    } else {
	        // Get the latest stock entry for the product based on the store
	        $stock_in = StockIn::where("product_id", $request->id)
	                           ->where("store_id", Auth::user()->store_id)  // Filter by store_id
	                           ->orderBy('id', 'desc')
	                           ->first();

	        // Add the product to the sell products list
	        $sell_product = new SellProduct;
	        $sell_product->invoice_id = $request->invoice_id;
	        $sell_product->product_id = $product->id;
	        $sell_product->purchase_price = $stock_in->purchase_price;
	        $sell_product->sell_price = $stock_in->sell_price;
	        $sell_product->product_name = $product->name;
	        $sell_product->store_id = Auth::user()->store_id;  // Assign the store_id
	        $sell_product->qty = 1;
	        $sell_product->save();
	    }

	    // Retrieve related sell products for the invoice
		$sellProducts = SellProduct::where('invoice_id', $invoice->id)->get();

		// Add a status field to sell products
		$sellProducts->transform(function ($item) {
		    $item->status = ''; // Set the status to an empty string
		    return $item;
		});

		// Retrieve related return sell products for the invoice
		$returnSellProducts = ReturnSellProduct::where('invoice_id', $invoice->id)->get();

		// Add a status field to return sell products
		$returnSellProducts->transform(function ($item) {
		    $item->status = 'Return'; // Set the status to 'Return'
		    return $item;
		});

		// Merge the two collections
		$mergedProducts = $sellProducts->merge($returnSellProducts);

		// Optionally, sort the merged collection (e.g., by product name or ID)
		$mergedProducts = $mergedProducts->sortBy('product_name')->values(); // Adjust field as needed

		// If needed, convert to array
		$mergedProductsArray = $mergedProducts->toArray();

		// Output or process the merged collection
		$mergedProducts;

	    return $mergedProducts;
	}

	public function sellProductUpdateQty(Request $request)
	{
	    if ($request->isMethod('post')) {
	        // Retrieve the sell product by id and filter by store_id
	        $sell_product = SellProduct::where('id', $request->id)
	                                   ->where('store_id', Auth::user()->store_id)  // Filter by store_id
	                                   ->first();

	        // Ensure the sell product exists
	        if (!$sell_product) {
	            return response()->json(['error' => 'Sell product not found'], 404);
	        }

	        if ($request->field == 'qty') {
	            // Retrieve the product and its associated stock and sell data for the correct store
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
	            ])->find($sell_product->product_id);

	            // Calculate total stock, sold products, and current cart quantity
	            $total_stock = $product->stockIns->sum('qty');
	            $total_sell_product = $product->sellProducts->sum('qty');
	            $total_return_sell_product = $product->sellProducts->sum('qty');
	            $total_cart_product = $request->value; // The quantity user wants to update

	            // Calculate available stock
	            $available_stock = ($total_stock + $total_return_sell_product) - ($total_sell_product + $total_cart_product);

	            // Check if there is sufficient stock
	            if ($available_stock < 0) {
	                return 'insufficient';
	            }
	        }

	        // If sell product exists, update its field (qty or other fields)
	        if ($sell_product) {
	            $field = $request->field;
	            $value = $request->value;

	            // Ensure that if the field is `qty`, the value is numeric
	            if ($field === 'qty') {
	                $value = is_numeric($value) ? (float) $value : 0; // Cast or validate the value
	            }

	            // Dynamically update the field
	            $sell_product->{$field} = $value;

	            // Save the updated sell product
	            $sell_product->save();
	        }

	        // Retrieve related sell products for the invoice
			$sellProducts = SellProduct::where('invoice_id', $invoice->id)->get();

			// Add a status field to sell products
			$sellProducts->transform(function ($item) {
			    $item->status = ''; // Set the status to an empty string
			    return $item;
			});

			// Retrieve related return sell products for the invoice
			$returnSellProducts = ReturnSellProduct::where('invoice_id', $invoice->id)->get();

			// Add a status field to return sell products
			$returnSellProducts->transform(function ($item) {
			    $item->status = 'Return'; // Set the status to 'Return'
			    return $item;
			});

			// Merge the two collections
			$mergedProducts = $sellProducts->merge($returnSellProducts);

			// Optionally, sort the merged collection (e.g., by product name or ID)
			$mergedProducts = $mergedProducts->sortBy('product_name')->values(); // Adjust field as needed

			// If needed, convert to array
			$mergedProductsArray = $mergedProducts->toArray();

			// Output or process the merged collection
			$mergedProducts;

	        return $mergedProducts;
	    }
	}

	public function sellProductDelete(Request $request)
	{
		if ($request->id) {
		    $sell_product = SellProduct::find($request->id);

		    if ($sell_product) {
		        ReturnSellProduct::create($sell_product->only([
		            'invoice_id', 'store_id', 'product_id', 'product_name', 
		            'purchase_price', 'sell_price', 'qty', 'vat', 'discount'
		        ]));

		        $sell_product->delete();
		    }

		    session()->flash('success', 'Product returned successfully');
		    return SellProduct::where('invoice_id', $request->invoice_id)->get();
		}

	}

	public function update(Request $request, $id)
	{
			DB::transaction(function () use ($request, $id) {
			    if ($request->customer_id == '' || $request->customer_id == 0) {
			        $customer = new Customer;
			        $customer->name = $request->name;
			        $customer->phone = $request->phone;
			        if($request->make_member=='Member'){
			        	$customer->discount = $request->discount;
			        	$customer->membership = $request->make_member;
			        }
			        $customer->save();
			        $customer_id = $customer->id;
			    } else {
			        $customer_id = $request->customer_id;
			        $customer = Customer::find($customer_id);
			        $customer->name = $request->name;
			        $customer->phone = $request->phone;
			        if($request->make_member=='Member'){
			        	$customer->discount = $request->discount;
			        	$customer->membership = $request->make_member;
			        }
			        $customer->save();
			    }

			    $due = ($request->total_payable_amount - $request->total_payments);
			    $invoice = Invoice::findOrFail($id);
			    $invoice->customer_id = $customer_id;
			    $invoice->total_bill = $request->total_payable_amount;
			    $invoice->product_return = $request->product_return;
			    $invoice->paid_amount = $request->total_payments;
			    $invoice->due_amount = ($due > 0) ? $due : 0;
			    $invoice->discount = $request->discount;
			    $invoice->less_amount = $request->less;
			    $invoice->sell_person_id = $request->sale_person_id;
			    $invoice->save();

			    // Helper function to process payment
			    function processCustomerPayment($invoice_id, $payment_type, $amount, $account_no_id, $from_account_no = null, $trx_note = null) {
			        $customer_payment = CustomerPayment::where('invoice_id', $invoice_id)->where('payment_type', $payment_type)->first();
			        if (!$customer_payment) {
			            $customer_payment = new CustomerPayment;
			            $customer_payment->store_id = Auth::user()->store_id;
			        }
			        $customer_payment->invoice_id = $invoice_id;
			        $customer_payment->payment_type = $payment_type;
			        $customer_payment->amount = $amount;
			        $customer_payment->bank_account_id = $account_no_id;
			        $customer_payment->payment_from_account_no = $from_account_no;
			        $customer_payment->payment_trx_note = $trx_note;
			        $customer_payment->save();
			    }

			    if ($request->cash_payment > 0) {
			        processCustomerPayment($invoice->id, 'cash_payment', $request->cash_payment, $request->cash_account_no_id);
			    }
			    if ($request->card_payment > 0) {
			        processCustomerPayment($invoice->id, 'card_payment', $request->card_payment, $request->card_account_no_id, $request->card_number, $request->card_type);
			    }
			    if ($request->mobile_payment > 0) {
			        processCustomerPayment($invoice->id, 'mobile_payment', $request->mobile_payment, $request->mobile_account_no_id, $request->sender_no, $request->trx_no);
			    }
			});

	        return $id;
            
	}

    public function index(Request $request)
	{
	    $stores = Store::all(); // Assuming you have a Store model

	    $query = Invoice::query();
	    $now = now();
	    $filterText = 'All Time'; // Default filter text
	    $storeName = 'All Stores'; // Default store text

	    // Handle date filtering
	    if ($request->filled('date_filter')) {
	        switch ($request->input('date_filter')) {
	            case 'today':
	                $query->whereDate('created_at', today());
	                $filterText = 'Today';
	                break;
	            case 'previous_day':
	                $query->whereDate('created_at', today()->subDay());
	                $filterText = 'Previous Day';
	                break;
	            case 'last_7_days':
	                $query->whereBetween('created_at', [$now->subDays(7), $now]);
	                $filterText = 'Last 7 Days';
	                break;
	            case 'this_month':
	                $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
	                $filterText = 'This Month';
	                break;
	            case 'this_year':
	                $query->whereYear('created_at', date('Y'));
	                $filterText = 'This Year';
	                break;
	            case 'custom':
	                if ($request->filled('start_date') && $request->filled('end_date')) {
	                    $query->whereBetween('created_at', [$request->input('start_date'), $request->input('end_date')]);
	                    $filterText = 'Custom Range (' . Carbon::parse($request->input('start_date'))->format('M d, Y') . ' - ' . Carbon::parse($request->input('end_date'))->format('M d, Y') . ')';
	                }
	                break;
	        }
	    }

	    // Role-based store filter
	    if (Auth::user()->role == 'station') {
	        // If the user's role is 'station', automatically apply their store_id
	        $query->where('store_id', Auth::user()->store_id);
	        $storeName = Store::find(Auth::user()->store_id)->name;
	    } else {
	        // Otherwise, allow the user to filter by store
	        if ($request->filled('store_id')) {
	            $query->where('store_id', $request->input('store_id'));
	            $store = Store::find($request->input('store_id'));
	            if ($store) {
	                $storeName = $store->name;
	            }
	        }
	    }

	    // Additional filters
	    if ($request->has('due')) {
	        $query->where('due_amount', '>', 0);
	    }

	    if ($request->has('full_paid')) {
	        $query->where('due_amount', 0);
	    }

	    // Fetch filtered invoices
	    $invoices = $query->get();

	    // Create the dynamic card header
	    $cardHeader = "Invoices Report for $filterText at $storeName";

	    return view('invoices.index', compact('invoices', 'stores', 'cardHeader'));
	}

	public function downloadInvoiceListPDF(Request $request)
	{
	    $query = Invoice::query();
	    $now = now();
	    $filterText = 'All Time'; // Default filter text
	    $storeName = 'All Stores'; // Default store text

	    // Handle date filtering
	    if ($request->filled('date_filter')) {
	        switch ($request->input('date_filter')) {
	            case 'today':
	                $query->whereDate('created_at', today());
	                $filterText = 'Today';
	                break;
	            case 'previous_day':
	                $query->whereDate('created_at', today()->subDay());
	                $filterText = 'Previous Day';
	                break;
	            case 'last_7_days':
	                $query->whereBetween('created_at', [$now->subDays(7), $now]);
	                $filterText = 'Last 7 Days';
	                break;
	            case 'this_month':
	                $query->whereMonth('created_at', date('m'))
	                      ->whereYear('created_at', date('Y'));
	                $filterText = 'This Month';
	                break;
	            case 'this_year':
	                $query->whereYear('created_at', date('Y'));
	                $filterText = 'This Year';
	                break;
	            case 'custom':
	                if ($request->filled('start_date') && $request->filled('end_date')) {
	                    $query->whereBetween('created_at', [
	                        $request->input('start_date'),
	                        $request->input('end_date')
	                    ]);
	                    $filterText = 'Custom Range (' . Carbon::parse($request->input('start_date'))->format('M d, Y') . ' - ' . Carbon::parse($request->input('end_date'))->format('M d, Y') . ')';
	                }
	                break;
	        }
	    }

	    // Role-based store filter logic
	    if (Auth::user()->role == 'station') {
	        // If the authenticated user's role is 'station', automatically filter by their store_id
	        $query->where('store_id', Auth::user()->store_id);
	        $storeName = Store::find(Auth::user()->store_id)->name;
	    } else {
	        // Otherwise, allow the user to filter by store
	        if ($request->filled('store_id')) {
	            $query->where('store_id', $request->input('store_id'));
	            $store = Store::find($request->input('store_id'));
	            if ($store) {
	                $storeName = $store->name;
	            }
	        }
	    }

	    // Additional filters for due and fully paid invoices
	    if ($request->has('due')) {
	        $query->where('due_amount', '>', 0);
	    }

	    if ($request->has('full_paid')) {
	        $query->where('due_amount', 0);
	    }

	    // Fetch the filtered invoices
	    $invoices = $query->get();

	    // Create the dynamic card header
	    $cardHeader = "Invoices Report for $filterText at $storeName";

	    // Generate PDF view (invoices.list_pdf is a Blade view file)
	    $pdf = PDF::loadView('invoices.pdf.list_pdf', compact('invoices', 'cardHeader'));

	    // Return the generated PDF for download
	    return $pdf->download('invoices.pdf');
	}

	public function show($id)
	{
	    $invoice = Invoice::with(['customer', 'sellProducts', 'returnSellProducts', 'payments', 'manager', 'sell_person', 'store'])->find($id);

	    if (!$invoice) {
	        return response()->json(['message' => 'Invoice not found.'], 404);
	    }

	    return view('invoices.single-invoice2', compact('invoice'));
	}

	// public function downloadInvoicePDF($id)
    // {
    //     $invoice = Invoice::with(['customer', 'sellProducts', 'returnSellProducts', 'payments', 'manager', 'sell_person'])->findOrFail($id);
        
    //     // Generate PDF view (invoices.pdf is a Blade view file)
    //     $pdf = PDF::loadView('invoices.pdf.single_invoice', compact('invoice'));

    //     // Return the generated PDF for download
    //     return $pdf->download('invoice_'.$id.'.pdf');
    // }

	public function downloadInvoicePDF($id)
	{
		$invoice = Invoice::with(['store', 'customer', 'sellProducts', 'returnSellProducts', 'payments'])->findOrFail($id);
		// return view('invoices.pdf.single_invoice', compact('invoice'));
		$pdf = PDF::loadView('invoices.pdf.single_invoice', compact('invoice'));
		return $pdf->download('invoice_' . $invoice->id . '.pdf');
	}
	
    
    public function jsonInvoice($id)
	{
	    $invoice = Invoice::with(['customer', 'sellProducts', 'returnSellProducts', 'payments', 'manager', 'sell_person'])->find($id);

	    if (!$invoice) {
	        return response()->json(['message' => 'Invoice not found.'], 404);
	    }

	    return response()->json($invoice);
	}

    public function destroy($id)
    {
        if (!empty($id)) {
			Invoice::findOrFail($id)->delete();
			CustomerPayment::where('invoice_id', $id)->delete();
			SellProduct::where('invoice_id', $id)->delete();
			ReturnSellProduct::where('invoice_id', $id)->delete();

			return redirect()->back()->with('flash_success', '
			    <script>
			    toaster.success("Invoice successfully deleted");
			    </script>
			');
		}
    }

    public function invoice_show_for_print($id) // for print
	{
	    $invoice = Invoice::with(['customer', 'sellProducts', 'returnSellProducts', 'payments', 'manager', 'sell_person', 'store'])->find($id);
	    if (!$invoice) {
	        return response()->json(['message' => 'Invoice not found.'], 404);
	    }
	    $printer_paper_size = $invoice->store->printer_paper_size;
	    return view("invoices.prints.$printer_paper_size", compact('invoice'));
	}

}
