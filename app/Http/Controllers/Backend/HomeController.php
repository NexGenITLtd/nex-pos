<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\Expense;
use App\Models\EmployeeSalary;
use App\Models\StockIn;
use App\Models\SellProduct;
use App\Models\ReturnSellProduct;
use App\Models\SupplierPayment;
use App\Models\BankAccount;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\SmsSetting;
use Carbon\Carbon;
use PDF;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        StockIn::notifyForTodayAlerts();

        // Initialize date filters
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $filterText = 'Today';

        // Check for date filter in the request
        if ($request->has('date_filter')) {
            switch ($request->input('date_filter')) {
                case 'previous_day':
                    $startDate = Carbon::yesterday()->startOfDay();
                    $endDate = Carbon::yesterday()->endOfDay();
                    $filterText = 'Previous Day';
                    break;
                case 'last_7_days':
                    $startDate = Carbon::today()->subDays(6)->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $filterText = 'Last 7 Days';
                    break;
                case 'this_month':
                    $startDate = Carbon::today()->startOfMonth();
                    $endDate = Carbon::today()->endOfMonth();
                    $filterText = 'This Month';
                    break;
                case 'this_year':
                    $startDate = Carbon::today()->startOfYear();
                    $endDate = Carbon::today()->endOfYear();
                    $filterText = 'This Year';
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        $filterText = 'Custom Range (' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y') . ')';
                    }
                    break;
            }
        }

        // Initialize store filter
        $store_id = $request->input('store_id');
        $storeName = 'All Stores'; // Default store name

        // If the authenticated user has the 'station' role, set the store_id to the user's store_id
        if (Auth::user()->role == 'station') {
            $store_id = Auth::user()->store_id;
        }

        // If a store is selected, fetch the store name
        if ($store_id) {
            $store = Store::find($store_id);
            if ($store) {
                $storeName = $store->name;
            }
        }

        // Build the card header dynamically
        $cardHeader = "Report for $filterText at $storeName";

        // Fetch total invoices based on the date range and store
        $invoicesQuery = Invoice::with('payments')->whereBetween('created_at', [$startDate, $endDate]);
        if ($store_id) {
            $invoicesQuery->where('store_id', $store_id);
        }
        $total_invoices = $invoicesQuery->count();
        $total_sales = $invoicesQuery->sum('total_bill');

        // Fetch payments grouped by bank_account_id
        $payments = $invoicesQuery->get()->flatMap(function ($invoice) {
            return $invoice->payments;
        })->groupBy('bank_account_id')->map(function ($group) {
            return $group->sum('amount');
        });

        // Fetch bank details for each account
        $bankDetails = BankAccount::whereIn('id', $payments->keys())->get()->mapWithKeys(function ($bankAccount) {
            return [$bankAccount->id => [
                'bank_name' => $bankAccount->bank_name,
                'account_no' => $bankAccount->account_no
            ]];
        });

        // Merge bank details with payment sums
        $paymentsWithDetails = $payments->map(function ($total, $accountId) use ($bankDetails) {
            return [
                'bank_name' => $bankDetails[$accountId]['bank_name'] ?? 'Unknown Bank',
                'account_no' => $bankDetails[$accountId]['account_no'] ?? 'Unknown Account',
                'total_amount' => $total
            ];
        });

        // Fetch total returned product amount from invoices
        $total_return_sell = $invoicesQuery->sum('product_return');

        // Fetch sold products' purchase price directly from sell_products table
        $soldProductsQuery = SellProduct::whereBetween('created_at', [$startDate, $endDate]);
        if ($store_id) {
            $soldProductsQuery->where('store_id', $store_id);
        }
        $soldProducts = $soldProductsQuery->get();
        $total_purchase_price = 0;

        foreach ($soldProducts as $product) {
            $total_purchase_price += $product->purchase_price * $product->qty;
        }

        // Calculate total profit (Sales - Purchase Price)
        $total_profit = ($total_sales + $total_return_sell) - $total_purchase_price;

        // Fetch total due amount
        $total_due = $invoicesQuery->where('due_amount', '>', 0)->sum('due_amount');

        // Fetch total supplier payment
        $supplierPaymentQuery = SupplierPayment::whereBetween('paid_date', [$startDate, $endDate]);
        if ($store_id) {
            $supplierPaymentQuery->where('store_id', $store_id);
        }
        $total_supplier_payment = $supplierPaymentQuery->sum('amount');

        // Fetch total expense
        $expenseQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        if ($store_id) {
            $expenseQuery->where('store_id', $store_id);
        }
        $total_expense = $expenseQuery->sum('amount');

        // Fetch total employee salary
        $salaryQuery = EmployeeSalary::whereBetween('paid_date', [$startDate, $endDate]);
        if ($store_id) {
            $salaryQuery->where('store_id', $store_id);
        }
        $total_salary = $salaryQuery->sum('amount');

        // Fetch bank account
        $bankAccountQuery = BankAccount::query();
        if ($store_id) {
            $bankAccountQuery->where('store_id', $store_id);
        }
        $bankAccounts = $bankAccountQuery->get();


        // Calculate cash in hand: $total_sales - ($total_due + $total_supplier_payment + $total_expense + $total_salary);
        $cash_in_hand = $total_sales - ($total_due + $total_supplier_payment + $total_expense + $total_salary);

        $totalCustomers = Customer::count();
        $totalSupplier = Supplier::count();
        $smsSetting = SmsSetting::where('id', Auth::user()->store_id)->first();

        return view('home', compact(
            'total_invoices',
            'total_sales',
            'total_purchase_price',
            'total_profit',
            'total_due',
            'total_return_sell', // Include total return sell in the view
            'total_supplier_payment',
            'total_expense',
            'total_salary',
            'cash_in_hand',
            'cardHeader', // Add the card header to the view
            'store_id', // to retain the store filter in the view
            'paymentsWithDetails',
            'bankAccounts',

            'totalCustomers',
            'smsSetting',
            'totalSupplier'
        ));
    }

    

    public function indexPdf(Request $request)
    {
        // Initialize date filters
        $startDate = Carbon::today()->startOfDay();
        $endDate = Carbon::today()->endOfDay();
        $filterText = 'Today';

        // Check for date filter in the request
        if ($request->has('date_filter')) {
            switch ($request->input('date_filter')) {
                case 'previous_day':
                    $startDate = Carbon::yesterday()->startOfDay();
                    $endDate = Carbon::yesterday()->endOfDay();
                    $filterText = 'Previous Day';
                    break;
                case 'last_7_days':
                    $startDate = Carbon::today()->subDays(6)->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    $filterText = 'Last 7 Days';
                    break;
                case 'this_month':
                    $startDate = Carbon::today()->startOfMonth();
                    $endDate = Carbon::today()->endOfMonth();
                    $filterText = 'This Month';
                    break;
                case 'this_year':
                    $startDate = Carbon::today()->startOfYear();
                    $endDate = Carbon::today()->endOfYear();
                    $filterText = 'This Year';
                    break;
                case 'custom':
                    if ($request->has('start_date') && $request->has('end_date')) {
                        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                        $filterText = 'Custom Range (' . $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y') . ')';
                    }
                    break;
            }
        }

        // Initialize store filter
        $store_id = $request->input('store_id');
        $storeName = 'All Stores'; // Default store name

        // If the authenticated user has the 'station' role, set the store_id to the user's store_id
        if (Auth::user()->role == 'station') {
            $store_id = Auth::user()->store_id;
        }

        // If a store is selected, fetch the store name
        if ($store_id) {
            $store = Store::find($store_id);
            if ($store) {
                $storeName = $store->name;
            }
        }

        // Build the card header dynamically
        $cardHeader = "Report for $filterText at $storeName";

        // Fetch total invoices based on the date range and store
        $invoicesQuery = Invoice::with('payments')->whereBetween('created_at', [$startDate, $endDate]);
        if ($store_id) {
            $invoicesQuery->where('store_id', $store_id);
        }
        $total_invoices = $invoicesQuery->count();
        $total_sales = $invoicesQuery->sum('total_bill');

        // Fetch total returned product amount from invoices
        $total_return_sell = $invoicesQuery->sum('product_return');


        // Fetch payments grouped by bank_account_id
        $payments = $invoicesQuery->get()->flatMap(function ($invoice) {
            return $invoice->payments;
        })->groupBy('bank_account_id')->map(function ($group) {
            return $group->sum('amount');
        });

        // Fetch bank details for each account
        $bankDetails = BankAccount::whereIn('id', $payments->keys())->get()->mapWithKeys(function ($bankAccount) {
            return [$bankAccount->id => [
                'bank_name' => $bankAccount->bank_name,
                'account_no' => $bankAccount->account_no
            ]];
        });

        // Merge bank details with payment sums
        $paymentsWithDetails = $payments->map(function ($total, $accountId) use ($bankDetails) {
            return [
                'bank_name' => $bankDetails[$accountId]['bank_name'] ?? 'Unknown Bank',
                'account_no' => $bankDetails[$accountId]['account_no'] ?? 'Unknown Account',
                'total_amount' => $total
            ];
        });

        // Fetch sold products' purchase price directly from sell_products table
        $soldProductsQuery = SellProduct::whereBetween('created_at', [$startDate, $endDate]);
        if ($store_id) {
            $soldProductsQuery->where('store_id', $store_id);
        }
        $soldProducts = $soldProductsQuery->get();
        $total_purchase_price = 0;

        foreach ($soldProducts as $product) {
            $total_purchase_price += $product->purchase_price * $product->qty;
        }

        // Calculate total profit (Sales - Purchase Price)
        $total_profit = ($total_sales+$total_return_sell) - $total_purchase_price;

        // Fetch total due amount
        $total_due = $invoicesQuery->where('due_amount', '>', 0)->sum('due_amount');


        // Fetch total supplier payment
        $supplierPaymentQuery = SupplierPayment::whereBetween('paid_date', [$startDate, $endDate]);
        if ($store_id) {
            $supplierPaymentQuery->where('store_id', $store_id);
        }
        $total_supplier_payment = $supplierPaymentQuery->sum('amount');

        // Fetch total expense
        $expenseQuery = Expense::whereBetween('expense_date', [$startDate, $endDate]);
        if ($store_id) {
            $expenseQuery->where('store_id', $store_id);
        }
        $total_expense = $expenseQuery->sum('amount');

        // Fetch total employee salary
        $salaryQuery = EmployeeSalary::whereBetween('paid_date', [$startDate, $endDate]);
        if ($store_id) {
            $salaryQuery->where('store_id', $store_id);
        }
        $total_salary = $salaryQuery->sum('amount');

        // Calculate cash in hand: $total_sales - ($total_due + $total_supplier_payment + $total_expense + $total_salary);
        $cash_in_hand = $total_sales - ($total_due + $total_supplier_payment + $total_expense + $total_salary);

        $pdf = PDF::loadView('pdf.home-pdf', compact(
            'total_invoices',
            'total_sales',
            'total_purchase_price',
            'total_profit',
            'total_due',
            'total_return_sell', // Include total return sell in the view
            'total_supplier_payment',
            'total_expense',
            'total_salary',
            'cash_in_hand',
            'cardHeader', // Add the card header to the view
            'store_id', // to retain the store filter in the view
            'paymentsWithDetails'
        ));

        // Return the generated PDF for download
        return $pdf->download('report_'.$cardHeader.'.pdf');

    }
}
