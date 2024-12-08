<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\SellProduct;
use App\Models\ReturnSellProduct;
use App\Models\Store;

class ProfitReportController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view profit-report')->only('index','show');
    }
    public function index(Request $request)
	{
	    // Retrieve the filter parameters from the request
	    $startDate = $request->input('start_date', ''); // Default to blank if not provided
	    $endDate = $request->input('end_date', ''); // Default to blank if not provided
	    $storeId = $request->input('store_id', ''); // Default to blank if not provided

	    // Default card header
	    $cardHeader = "Profit Report";

	    // Dynamically build the card header based on filter parameters
	    if ($startDate && $endDate) {
	        $cardHeader .= " from {$startDate} to {$endDate}";
	    } elseif ($startDate) {
	        $cardHeader .= " from {$startDate}";
	    } elseif ($endDate) {
	        $cardHeader .= " until {$endDate}";
	    } else {
	        $cardHeader .= " for All Times"; // Default to "All Times" if no date range is provided
	    }

	    // Handle store filter: if store_id is provided, show specific store, otherwise "All Stores"
	    if ($storeId) {
	        $store = Store::find($storeId);
	        $cardHeader .= " for " . ($store ? $store->name : 'selected store');
	    } else {
	        $cardHeader .= " for All Stores"; // Default to "All Stores" if no store is selected
	    }

	    // Query for Total Sales (from total_bill in invoices)
	    $invoicesQuery = Invoice::query();

	    // Only apply date filters if startDate and endDate are provided
	    if ($startDate) {
	        $invoicesQuery->where('created_at', '>=', $startDate);
	    }

	    if ($endDate) {
	        $invoicesQuery->where('created_at', '<=', $endDate);
	    }

	    if ($storeId) {
	        $invoicesQuery->where('store_id', $storeId);
	    }

	    $totalSales = $invoicesQuery->sum('total_bill');

	    // Query for Total Returns (from product_return in invoices)
	    $totalReturns = $invoicesQuery->sum('product_return');

	    // Net Sales (Sales - Returns)
	    $netSales = $totalSales;

	    // Query for Total Purchase Cost of Sold Products (from sell_products)
	    $sellProductsQuery = SellProduct::query();

	    if ($startDate) {
	        $sellProductsQuery->whereHas('invoice', function($query) use ($startDate) {
	            $query->where('created_at', '>=', $startDate);
	        });
	    }

	    if ($endDate) {
	        $sellProductsQuery->whereHas('invoice', function($query) use ($endDate) {
	            $query->where('created_at', '<=', $endDate);
	        });
	    }

	    if ($storeId) {
	        $sellProductsQuery->whereHas('invoice', function($query) use ($storeId) {
	            $query->where('store_id', $storeId);
	        });
	    }

	    $totalPurchaseCost = $sellProductsQuery->selectRaw('SUM(purchase_price * qty) as total_purchase_cost')->value('total_purchase_cost');

	    // Query for Total Return Purchase Cost (from return_sell_products)
	    $returnSellProductsQuery = ReturnSellProduct::query();

	    if ($startDate) {
	        $returnSellProductsQuery->whereHas('invoice', function($query) use ($startDate) {
	            $query->where('created_at', '>=', $startDate);
	        });
	    }

	    if ($endDate) {
	        $returnSellProductsQuery->whereHas('invoice', function($query) use ($endDate) {
	            $query->where('created_at', '<=', $endDate);
	        });
	    }

	    if ($storeId) {
	        $returnSellProductsQuery->whereHas('invoice', function($query) use ($storeId) {
	            $query->where('store_id', $storeId);
	        });
	    }

	    $totalReturnPurchaseCost = $returnSellProductsQuery->selectRaw('SUM(purchase_price * qty) as total_return_purchase_cost')->value('total_return_purchase_cost');

	    // Net Purchase Cost (Purchase Cost - Returned Purchase Cost)
	    $netPurchaseCost = $totalPurchaseCost - $totalReturnPurchaseCost;

	    // Gross Profit
	    $grossProfit = $netSales - $netPurchaseCost;

	    // Fetch stores for dropdown
	    $stores = Store::all();

	    // Return the view with the data
	    return view('reports.profit', [
	        'stores' => $stores,
	        'cardHeader' => $cardHeader,
	        'startDate' => $startDate,
	        'endDate' => $endDate,
	        'storeId' => $storeId,
	        'totalSales' => $totalSales,
	        'totalReturns' => $totalReturns,
	        'netSales' => $netSales,
	        'totalPurchaseCost' => $totalPurchaseCost,
	        'totalReturnPurchaseCost' => $totalReturnPurchaseCost,
	        'netPurchaseCost' => $netPurchaseCost,
	        'grossProfit' => $grossProfit,
	    ]);
	}


}
