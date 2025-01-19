<?php

namespace App\Http\Controllers\Backend;

use App\Models\Transaction;
use App\Models\Store;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view user', ['only' => ['index']]);
    }

    public function index(Request $request)
    {
        $query = Transaction::query();

        // Filter by store_id if provided
        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        // Filter by bank_account_id if provided
        if ($request->bank_account_id) {
            $query->where('bank_account_id', $request->bank_account_id);
        }

        // Filter by date range or a single date
        if ($request->start_date && $request->end_date) {
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();

            if ($start_date->equalTo($end_date)) {
                // Single date filter
                $query->whereDate('created_at', $start_date);
            } else {
                // Date range filter
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }
        }

        // Retrieve transactions with relationships and paginate
        $transactions = $query->with(['store', 'bankAccount', 'creator'])->latest()->paginate(100);

        // Retrieve stores and bank accounts for the view
        $stores = Store::all();
        $bankAccounts = BankAccount::all();

        return view('transactions.index', [
            'transactions' => $transactions,
            'stores' => $stores,
            'bankAccounts' => $bankAccounts,
        ]);
    }


}
