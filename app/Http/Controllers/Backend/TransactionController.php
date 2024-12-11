<?php

namespace App\Http\Controllers\Backend;

use App\Models\Transaction;
use App\Models\Store;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        if ($request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->bank_account_id) {
            $query->where('bank_account_id', $request->bank_account_id);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $transactions = $query->with(['store', 'bankAccount', 'creator'])->latest()->paginate(100);
        // Pass stores and bank accounts to the view
        $stores = Store::all();  // Get all stores
        $bankAccounts = BankAccount::all();  // Get all bank accounts
        return view('transactions.index', [
            'transactions' => $transactions,
            'stores' => Store::all(),
            'bankAccounts' => BankAccount::all(),
        ]);
    }

}
