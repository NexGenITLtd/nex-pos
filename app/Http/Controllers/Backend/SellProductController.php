<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellProduct;
use App\Models\Store;
use PDF;
use Auth;
class SellProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show sales report')->only('index','downloadPDF');
    }
    public function index(Request $request)
    {
        $query = SellProduct::query();

        // Join the invoices table to access the store_id field
        $query->join('invoices', 'sell_products.invoice_id', '=', 'invoices.id');

        // Initialize default date filter text
        $filterText = 'Today'; // Default is 'Today'
        $now = now();

        // Apply date filters
        if ($request->has('date_filter') && $request->date_filter) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('sell_products.created_at', $now->toDateString());
                    $filterText = 'Today';
                    break;
                case 'previous_day':
                    $query->whereDate('sell_products.created_at', $now->subDay()->toDateString());
                    $filterText = 'Previous Day';
                    break;
                case 'last_7_days':
                    $query->whereBetween('sell_products.created_at', [$now->subDays(7), $now]);
                    $filterText = 'Last 7 Days';
                    break;
                case 'this_month':
                    $query->whereMonth('sell_products.created_at', $now->month)->whereYear('sell_products.created_at', $now->year);
                    $filterText = 'This Month';
                    break;
                case 'this_year':
                    $query->whereYear('sell_products.created_at', $now->year);
                    $filterText = 'This Year';
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('sell_products.created_at', [$request->start_date, $request->end_date]);
                        $filterText = 'Custom Range (' . Carbon::parse($request->start_date)->format('M d, Y') . ' - ' . Carbon::parse($request->end_date)->format('M d, Y') . ')';
                    }
                    break;
            }
        }

        // Initialize store filter and store name
        $storeName = 'All Stores'; // Default is 'All Stores'

        // Check the user's role and apply store filters
        if (Auth::user()->role == 'station') {
            // If the user's role is 'station', automatically filter by their store
            $query->where('invoices.store_id', Auth::user()->store_id);
            $storeName = Store::find(Auth::user()->store_id)->name; // Get the store name for the station user
        } else {
            // Otherwise, allow filtering by store from the request
            if ($request->has('store_id') && $request->store_id) {
                $query->where('invoices.store_id', $request->store_id);
                $store = Store::find($request->store_id);
                if ($store) {
                    $storeName = $store->name; // Get the store name based on the selected store
                }
            }
        }

        // Fetch the filtered data
        $sellProducts = $query->select('sell_products.*')->get();
        $stores = Store::all(); // Retrieve all stores for filtering options in the view

        // Create dynamic card header
        $cardHeader = "Sales Report for $filterText at $storeName";

        return view('sell_products.index', compact('sellProducts', 'stores', 'cardHeader'));
    }


    public function downloadPDF(Request $request)
    {
        $query = SellProduct::query();

        // Join the invoices table to access the store_id field
        $query->join('invoices', 'sell_products.invoice_id', '=', 'invoices.id');

        // Initialize default date filter text
        $filterText = 'Today'; // Default is 'Today'
        $now = now();

        // Apply date filters
        if ($request->has('date_filter') && $request->date_filter) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('sell_products.created_at', $now->toDateString());
                    $filterText = 'Today';
                    break;
                case 'previous_day':
                    $query->whereDate('sell_products.created_at', $now->subDay()->toDateString());
                    $filterText = 'Previous Day';
                    break;
                case 'last_7_days':
                    $query->whereBetween('sell_products.created_at', [$now->subDays(7), $now]);
                    $filterText = 'Last 7 Days';
                    break;
                case 'this_month':
                    $query->whereMonth('sell_products.created_at', $now->month)->whereYear('sell_products.created_at', $now->year);
                    $filterText = 'This Month';
                    break;
                case 'this_year':
                    $query->whereYear('sell_products.created_at', $now->year);
                    $filterText = 'This Year';
                    break;
                case 'custom':
                    if ($request->start_date && $request->end_date) {
                        $query->whereBetween('sell_products.created_at', [$request->start_date, $request->end_date]);
                        $filterText = 'Custom Range (' . Carbon::parse($request->start_date)->format('M d, Y') . ' - ' . Carbon::parse($request->end_date)->format('M d, Y') . ')';
                    }
                    break;
            }
        }

        // Initialize store filter and store name
        $storeName = 'All Stores'; // Default is 'All Stores'

        // Check the user's role and apply store filters
        if (Auth::user()->role == 'station') {
            // If the user's role is 'station', automatically filter by their store
            $query->where('invoices.store_id', Auth::user()->store_id);
            $storeName = Store::find(Auth::user()->store_id)->name; // Get the store name for the station user
        } else {
            // Otherwise, allow filtering by store from the request
            if ($request->has('store_id') && $request->store_id) {
                $query->where('invoices.store_id', $request->store_id);
                $store = Store::find($request->store_id);
                if ($store) {
                    $storeName = $store->name; // Get the store name based on the selected store
                }
            }
        }

        // Fetch the filtered data
        $sellProducts = $query->select('sell_products.*')->get();

        // Create dynamic card header
        $cardHeader = "Sales Report for $filterText at $storeName";

        // Pass the filtered data and card header to the view for PDF generation
        $pdf = Pdf::loadView('sell_products.pdf', compact('sellProducts', 'cardHeader'));

        // Download the generated PDF
        return $pdf->download('sell_products.pdf');
    }

}
