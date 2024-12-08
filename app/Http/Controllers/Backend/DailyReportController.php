<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Store;
use App\Models\Product;
use App\Models\EmployeeSalary;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\SupplierPayment;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use PDF;
use Auth;

use App\Models\DailyReport;

class DailyReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view daily-report')->only('index','show');
        $this->middleware('permission:create daily-report')->only('create', 'store');
        $this->middleware('permission:update daily-report')->only('edit', 'update');
        $this->middleware('permission:delete daily-report')->only('destroy');
    }

    public function index(Request $request)
    {
        $stores = Store::get();

        // Initialize query
        $query = DailyReport::query();

        // Determine filter type
        $dateFilter = $request->input('date_filter', 'this_month'); // Default to "this_month"
        $filterDescription = 'This Month\'s Reports'; // Default description

        if ($dateFilter) {
            if ($dateFilter === 'today') {
                $query->where('date', now()->toDateString());
                $filterDescription = 'Today\'s Reports';
            } elseif ($dateFilter === 'previous_day') {
                $query->where('date', now()->subDay()->toDateString());
                $filterDescription = 'Previous Day\'s Reports';
            } elseif ($dateFilter === 'last_7_days') {
                $query->whereBetween('date', [now()->subDays(7)->toDateString(), now()->toDateString()]);
                $filterDescription = 'Last 7 Days Reports';
            } elseif ($dateFilter === 'this_month') {
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                $filterDescription = 'This Month\'s Reports';
            } elseif ($dateFilter === 'this_year') {
                $query->whereYear('date', now()->year);
                $filterDescription = 'This Year\'s Reports';
            } elseif ($dateFilter === 'all_time') {
                $filterDescription = 'All Time Reports';
            } elseif ($dateFilter === 'custom') {
                $startDate = $request->input('start_date');
                $endDate = $request->input('end_date');
                if ($startDate && $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                    $filterDescription = "Custom Range: $startDate to $endDate";
                }
            }
        }

        // Filter by store
        $storeId = $request->input('store_id');
        if ($storeId) {
            $store = $stores->where('id', $storeId)->first();
            $storeName = $store ? $store->name : 'Unknown Store';
            $filterDescription .= " (Store: $storeName)";
            $query->where('store_id', $storeId);
        }

        // Fetch results
        $reports = $query->get();

        // Return view with filter description
        return view('daily_reports.index', compact('reports', 'stores', 'filterDescription', 'dateFilter'));
    }

    public function create(Request $request)
    {
        // Initialize default values for the report
        $total_invoices = 0;
        $total_sales = 0;
        $total_purchase_price = 0;
        $total_profit = 0;
        $total_due = 0;
        $total_return_sell = 0;
        $total_supplier_payment = 0;
        $total_expense = 0;
        $total_salary = 0;
        $cash_in_hand = 0;
        $previous_cash_in_hand = 0;

        // Retrieve form data
        $store_id = $request->input('store_id', 0); // Default to 0 if not selected
        if ($store_id == 0) {
            $store_id = Store::first()->id; // Get the first store ID if no store ID is selected
        }

        $date = $request->input('date', Carbon::today()->toDateString()); // Default to today's date

        // Fetch the list of stores to populate the select dropdown
        $stores = Store::all();

        // Retrieve the store name based on store_id
        $store = Store::find($store_id);
        $store_name = $store ? $store->name : 'Default Store'; // Default to 'Default Store' if store not found

        // Initialize cardHeader with dynamic content and store name
        $cardHeader = 'Daily Report Overview for ' . Carbon::parse($date)->format('l, j F, Y') . ' - ' . $store_name;

        // Prepare date filter
        $targetDate = Carbon::parse($date)->startOfDay();

        // Fetch the last report for the previous day's cash in hand by filtering with the `date` column
        $lastPreviousCash = DailyReport::where('store_id', $store_id)
                                        ->where('date', '<', $targetDate) // Ensure it's a previous day's report
                                        ->latest('date') // Sort by the latest date before today
                                        ->first();

        // Fetch invoices for the current date and store
        $invoicesQuery = Invoice::whereDate('created_at', $targetDate);
        if ($store_id) {
            $invoicesQuery->where('store_id', $store_id);
        }
        $total_invoices = $invoicesQuery->count();
        $total_sales = $invoicesQuery->sum('total_bill');
        $total_return_sell = $invoicesQuery->sum('product_return');

        // Fetch sold products and calculate total purchase price
        $soldProductsQuery = SellProduct::whereDate('created_at', $targetDate);
        if ($store_id) {
            $soldProductsQuery->where('store_id', $store_id);
        }
        $soldProducts = $soldProductsQuery->get();
        $total_purchase_price = $soldProducts->sum(function ($product) {
            return $product->purchase_price * $product->qty;
        });

        // Calculate profit and other metrics
        $total_profit = ($total_sales + $total_return_sell) - $total_purchase_price;
        $total_due = $invoicesQuery->where('due_amount', '>', 0)->sum('due_amount');

        // Fetch supplier payments
        $supplierPaymentQuery = SupplierPayment::whereDate('paid_date', $targetDate);
        if ($store_id) {
            $supplierPaymentQuery->where('store_id', $store_id);
        }
        $total_supplier_payment = $supplierPaymentQuery->sum('amount');

        // Fetch expenses
        $expenseQuery = Expense::whereDate('expense_date', $targetDate);
        if ($store_id) {
            $expenseQuery->where('store_id', $store_id);
        }
        $total_expense = $expenseQuery->sum('amount');

        // Fetch employee salaries
        $salaryQuery = EmployeeSalary::whereDate('paid_date', $targetDate);
        if ($store_id) {
            $salaryQuery->where('store_id', $store_id);
        }
        $total_salary = $salaryQuery->sum('amount');

        // Set the previous day's cash in hand if available
        $previous_cash_in_hand = $lastPreviousCash ? $lastPreviousCash->cash_in_hand : 0;

        // Calculate the cash in hand for the current day
        $cash_in_hand = $previous_cash_in_hand + $total_sales - $total_supplier_payment - $total_expense;

        // Return the view with data
        return view('daily_reports.create', compact(
            'total_invoices',
            'total_sales',
            'total_purchase_price',
            'total_profit',
            'total_due',
            'total_return_sell',
            'total_supplier_payment',
            'total_expense',
            'total_salary',
            'cash_in_hand',
            'previous_cash_in_hand',
            'store_id',
            'store_name',
            'cardHeader',
            'date',
            'stores'
        ));
    }

    public function store(Request $request)
	{
        // return $request->all();
	    // Validate the input
	    $validated = $request->validate([
	        'date' => 'required|date',
	        'total_invoices' => 'required|numeric',
	        'previous_cash_in_hand' => 'numeric',
	        'extra_cash' => 'numeric',
	        'total_sales' => 'required|numeric',
	        'total_return_sell' => 'required|numeric',
	        'total_purchase_price' => 'required|numeric',
	        'total_profit' => 'required|numeric',
	        'total_due' => 'required|numeric',
	        'total_supplier_payment' => 'required|numeric',
	        'total_expense' => 'required|numeric',
	        'total_salary' => 'required|numeric',
	        'extra_expense' => 'numeric',
	        'owner_deposit' => 'numeric',
	        'bank_deposit' => 'numeric',
	        'cash_in_hand' => 'required|numeric',
            'net_profit' => 'required|numeric',

	    ]);

	    // Add store_id from the authenticated user and date from the input field
	    $validated['store_id'] = $request->input('store_id');
	    $validated['date'] = $request->input('date');

	    // Create the report
	    DailyReport::create($validated);

	    return redirect()->route('dailyreports.index')->with('success', 'Daily Report saved successfully!');
	}

    public function edit($id)
    {
        
        $dailyreport = DailyReport::findOrFail($id);
        $store = Store::find($dailyreport->store_id);
        $store_name = $store ? $store->name : 'Default Store'; // Default to 'Default Store' if store not found

        // Initialize cardHeader with dynamic content and store name
        $cardHeader = 'Daily Report Overview for ' . Carbon::parse($store->date)->format('l, j F, Y') . ' - ' . $store_name; 
        
        return view('daily_reports.edit', compact('dailyreport','cardHeader'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'total_invoices' => 'required|numeric',
            'previous_cash_in_hand' => 'required|numeric',
            'extra_cash' => 'required|numeric',
            'total_sales' => 'required|numeric',
            'total_return_sell' => 'required|numeric',
            'total_purchase_price' => 'required|numeric',
            'total_profit' => 'required|numeric',
            'total_due' => 'required|numeric',
            'total_supplier_payment' => 'required|numeric',
            'total_expense' => 'required|numeric',
            'total_salary' => 'required|numeric',
            'extra_expense' => 'required|numeric',
            'owner_deposit' => 'required|numeric',
            'bank_deposit' => 'required|numeric',
            'cash_in_hand' => 'required|numeric',
            'net_profit' => 'required|numeric',

        ]);

        $report = DailyReport::findOrFail($id);
        $report->update($validated);  // Update the report with validated data

        return redirect()->route('dailyreports.index')->with('flash_sucess', 'Report updated successfully.');
    }

    public function destroy($id)
    {
        $report = DailyReport::findOrFail($id)->delete();

        return redirect()->back()->with('flash_sucess', 'Report deleted successfully.');
    }

    public function show($id)
    {
        
        $dailyreport = DailyReport::findOrFail($id);
        $store = Store::find($dailyreport->store_id);
        $store_name = $store ? $store->name : 'Default Store'; // Default to 'Default Store' if store not found

        // Initialize cardHeader with dynamic content and store name
        $cardHeader = 'Daily Report Overview for ' . Carbon::parse($store->date)->format('l, j F, Y') . ' - ' . $store_name; 
        
        return view('daily_reports.show', compact('dailyreport','cardHeader'));
    }
}

