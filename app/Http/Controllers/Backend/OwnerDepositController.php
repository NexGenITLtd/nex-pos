<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\OwnerDeposit;
use App\Models\Store;
use App\Models\BankAccount;
use App\Models\Transaction;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Auth;

class OwnerDepositController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view user', ['only' => ['index','show']]);
        $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:update user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    

    public function index(Request $request)
    {
        $query = OwnerDeposit::query();
        $filters = [];

        // Filter by date
        if ($request->filled('date_filter')) {
            switch ($request->input('date_filter')) {
                case 'today':
                    $query->whereDate('date', Carbon::today());
                    $filters[] = 'Today';
                    break;
                case 'previous_day':
                    $query->whereDate('date', Carbon::yesterday());
                    $filters[] = 'Previous Day';
                    break;
                case 'last_7_days':
                    $query->whereBetween('date', [Carbon::now()->subDays(7), Carbon::now()]);
                    $filters[] = 'Last 7 Days';
                    break;
                case 'this_month':
                    $query->whereMonth('date', Carbon::now()->month)
                        ->whereYear('date', Carbon::now()->year);
                    $filters[] = 'This Month';
                    break;
                case 'this_year':
                    $query->whereYear('date', Carbon::now()->year);
                    $filters[] = 'This Year';
                    break;
                case 'custom':
                    if ($request->filled(['start_date', 'end_date'])) {
                        $query->whereBetween('date', [
                            Carbon::parse($request->input('start_date')),
                            Carbon::parse($request->input('end_date'))
                        ]);
                        $filters[] = 'Custom Range (' . $request->input('start_date') . ' to ' . $request->input('end_date') . ')';
                    }
                    break;
            }
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->input('transaction_type'));
            $filters[] = ucfirst($request->input('transaction_type'));
        }

        // Filter by store
        if ($request->filled('store_id')) {
            $store = Store::find($request->input('store_id'));
            if ($store) {
                $query->where('store_id', $store->id);
                $filters[] = 'Store: ' . $store->name;
            }
        }

        $stores = Store::all();
        $ownerDeposits = $query->get(); // Or paginate if needed

        // Create cardHeader summary
        $cardHeader = count($filters) > 0 ? implode(', ', $filters) : 'All Records';

        return view('owner_deposits.index', compact('ownerDeposits', 'stores', 'cardHeader'));
    }




    public function create()
    {
        // Pass stores and bank accounts to the view
        $stores = Store::all();  // Get all stores
        $bankAccounts = BankAccount::all();  // Get all bank accounts

        return view('owner_deposits.create', compact('stores', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:deposit,withdrawal', // Validate transaction type
            'note' => 'nullable|string|max:255',
        ]);

        // Prepare data for insertion in OwnerDeposit
        $validated['date'] = $request->date;
        
        // Create the deposit/withdrawal record in OwnerDeposit
        $owner_deposit = OwnerDeposit::create($validated);

        // Record the transaction in the Transaction table
        $debit = 0;
        $credit = 0;

        if ($request->transaction_type == 'deposit') {
            // For a deposit, we credit the bank account
            $debit = $request->amount;
        } else {
            // For a withdrawal, we debit the bank account
            $credit = $request->amount;
        }

        // Create a transaction record
        Transaction::createTransaction(
            $request->store_id, // Store ID
            $request->bank_account_id, // Bank Account ID
            Auth::user()->id, // Logged-in user ID
            $debit, // Debit amount for withdrawal
            $credit, // Credit amount for deposit
            "Owner Transaction - Type: {$request->transaction_type}, Amount: {$request->amount}, Note: {$request->note}"
        );

        return redirect()->route('owner-deposits.index')->with('success', 'Owner transaction recorded successfully.');
    }


    public function show(OwnerDeposit $ownerDeposit)
    {
        return view('owner_deposits.show', compact('ownerDeposit'));
    }

    public function edit(OwnerDeposit $ownerDeposit)
    {
        $stores = Store::all();  // Get all stores
        $bankAccounts = BankAccount::all();  // Get all bank accounts
        return view('owner_deposits.edit', compact('stores','bankAccounts','ownerDeposit'));
    }

    public function update(Request $request, OwnerDeposit $ownerDeposit)
{
    // Validation rules
    $validated = $request->validate([
        'store_id' => 'required|exists:stores,id',
        'date' => 'required|date',
        'bank_account_id' => 'required|exists:bank_accounts,id',
        'amount' => 'required|numeric|min:0',
        'transaction_type' => 'required|in:deposit,withdrawal', // Validate transaction type
        'note' => 'nullable|string|max:255',
    ]);

    // Store the old transaction details for comparison
    $oldTransactionType = $ownerDeposit->transaction_type;
    $oldAmount = $ownerDeposit->amount;
    $oldBankAccountId = $ownerDeposit->bank_account_id;

    // Update the deposit/withdrawal record
    $ownerDeposit->update($validated);

    // Check if transaction details have changed
    if (
        $oldTransactionType != $ownerDeposit->transaction_type ||
        $oldAmount != $ownerDeposit->amount ||
        $oldBankAccountId != $ownerDeposit->bank_account_id
    ) {
        // Reverse the old transaction
        $reverseDebit = $oldTransactionType == 'deposit' ? $oldAmount : 0;
        $reverseCredit = $oldTransactionType == 'withdrawal' ? $oldAmount : 0;

        Transaction::createTransaction(
            $ownerDeposit->store_id,
            $oldBankAccountId,
            Auth::user()->id,
            -$reverseDebit, // Reverse debit
            -$reverseCredit, // Reverse credit
            "Reversed Owner Transaction - Type: {$oldTransactionType}, Amount: {$oldAmount}, Note: {$ownerDeposit->note}"
        );

        // Record the new transaction
        $debit = $ownerDeposit->transaction_type == 'deposit' ? $ownerDeposit->amount : 0;
        $credit = $ownerDeposit->transaction_type == 'withdrawal' ? $ownerDeposit->amount : 0;

        Transaction::createTransaction(
            $ownerDeposit->store_id,
            $ownerDeposit->bank_account_id,
            Auth::user()->id,
            $debit, // New debit for deposit
            $credit, // New credit for withdrawal
            "Updated Owner Transaction - Type: {$ownerDeposit->transaction_type}, Amount: {$ownerDeposit->amount}, Note: {$ownerDeposit->note}"
        );
    }

    return redirect()->route('owner-deposits.index')->with('success', 'Owner transaction updated successfully.');
}



    public function destroy(OwnerDeposit $ownerDeposit)
    {
        // Get the details of the transaction for reversal
        $store_id = $ownerDeposit->store_id;
        $bank_account_id = $ownerDeposit->bank_account_id;
        $amount = $ownerDeposit->amount;
        $transaction_type = $ownerDeposit->transaction_type; // 'deposit' or 'withdrawal'

        // Record the transaction reversal (debit and credit flip)
        $debit = 0;
        $credit = 0;

        if ($transaction_type == 'deposit') {
            // If it was a deposit, reverse it by crediting the bank account
            $credit = $amount;
        } else {
            // If it was a withdrawal, reverse it by debiting the bank account
            $debit = $amount;
        }

        // Create the reversal transaction
        Transaction::createTransaction(
            $store_id,
            $bank_account_id,
            Auth::user()->id,
            $debit,
            $credit,
            "Reversed Owner Transaction - Type: {$transaction_type}, Amount: {$amount}"
        );

        // Delete the OwnerDeposit record
        $ownerDeposit->delete();

        // Redirect with success message
        return redirect()->route('owner-deposits.index')->with('success', 'Owner transaction deleted successfully.');
    }

}
