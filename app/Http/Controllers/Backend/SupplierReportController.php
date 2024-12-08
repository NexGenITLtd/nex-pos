<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Store;

class SupplierReportController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view supplier-report')->only('index');
    }

	public function index(Request $request)
	{
	    // Get filters from the request
	    $startDate = $request->input('start_date', ''); // Default to blank if not provided
	    $endDate = $request->input('end_date', ''); // Default to blank if not provided
	    $storeId = $request->input('store_id', ''); // Default to blank if not provided
	    $supplierId = $request->input('supplier_id', ''); // Default to blank if not provided

	    // Initialize card header
	    $cardHeader = "Supplier Report";

	    // Append filter information to the header
	    if (!empty($startDate) && !empty($endDate)) {
	        $cardHeader .= " from " . date('d-m-Y', strtotime($startDate)) . " to " . date('d-m-Y', strtotime($endDate));
	    } elseif (!empty($startDate)) {
	        $cardHeader .= " from " . date('d-m-Y', strtotime($startDate));
	    } elseif (!empty($endDate)) {
	        $cardHeader .= " up to " . date('d-m-Y', strtotime($endDate));
	    }

	    if (!empty($storeId)) {
	        $store = Store::find($storeId);
	        $cardHeader .= " for Store: " . ($store ? $store->name : "Unknown Store");
	    }

	    if (!empty($supplierId)) {
	        $supplier = Supplier::find($supplierId);
	        $cardHeader .= " for Supplier: " . ($supplier ? $supplier->name : "Unknown Supplier");
	    }

	    // Query suppliers with their relationships
	    $query = Supplier::with(['stockIns', 'supplierPayments']);

	    if (!empty($supplierId)) {
	        $query->where('id', $supplierId);
	    }

	    // Fetch suppliers and calculate totals
	    $suppliers = $query->get()->map(function ($supplier) use ($startDate, $endDate, $storeId) {
	        $filteredStockIns = $supplier->stockIns->filter(function ($stockIn) use ($startDate, $endDate, $storeId) {
	            $dateMatch = (empty($startDate) || $stockIn->created_at >= $startDate) &&
	                         (empty($endDate) || $stockIn->created_at <= $endDate);
	            $storeMatch = (empty($storeId) || $stockIn->store_id == $storeId);

	            return $dateMatch && $storeMatch;
	        });

	        $totalPurchase = $filteredStockIns->sum(function ($stockIn) {
	            return $stockIn->purchase_price * $stockIn->qty;
	        });

	        $filteredPayments = $supplier->supplierPayments->filter(function ($payment) use ($startDate, $endDate, $storeId) {
	            $dateMatch = (empty($startDate) || $payment->paid_date >= $startDate) &&
	                         (empty($endDate) || $payment->paid_date <= $endDate);
	            $storeMatch = (empty($storeId) || $payment->store_id == $storeId);

	            return $dateMatch && $storeMatch;
	        });

	        $totalPayment = $filteredPayments->sum('amount');

	        // Calculate due amount or advance payment
	        $due = max(0, $totalPurchase - $totalPayment); // If payment is less than purchase
	        $advance = max(0, $totalPayment - $totalPurchase); // If payment exceeds purchase

	        return [
	            'supplier_id' => $supplier->id,
	            'name' => $supplier->name,
	            'total_purchase' => $totalPurchase,
	            'total_payment' => $totalPayment,
	            'due' => $due,
	            'advance' => $advance,
	        ];
	    });

	    // Fetch dropdown options
	    $stores = Store::all();
	    $allSuppliers = Supplier::all();

	    return view('reports.supplier', compact('suppliers', 'startDate', 'endDate', 'storeId', 'supplierId', 'stores', 'allSuppliers', 'cardHeader'));
	}



}
