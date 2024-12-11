<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreBankAccountRequest;
use App\Models\BankAccount;
use App\Models\Store;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Permission;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view bank-account')->only('index');
        $this->middleware('permission:create bank-account')->only(['create', 'store']);
        $this->middleware('permission:update bank-account')->only(['edit', 'update']);
        $this->middleware('permission:delete bank-account')->only('destroy');
    }

    public function index()
    {
        $accounts = BankAccount::orderBy('id')->paginate(100);
        return view("banks.accounts.index")->with(compact('accounts'));
    }

    public function create()
    {
        $stores = Store::get();
        return view("banks.accounts.create")->with(compact('stores'));
    }

    public function store(StoreBankAccountRequest $request)
    {
        // If validation passes, create the new bank account
        $account = new BankAccount();
        $account->bank_name = $request->bank_name;
        $account->account_no = $request->account_no;
        $account->account_type = $request->account_type;
        $account->initial_balance = $request->initial_balance ?? 0;
        $account->store_id = $request->store_id;
        $account->current_balance = $request->initial_balance ?? 0;  // Initialize current_balance
        $account->save();

        // Record the transaction for creating the bank account
        // Transaction::createTransaction(
        //     $request->store_id,
        //     $account->id,
        //     0, // No debit for creating the bank account
        //     $request->initial_balance ?? 0, // Credit the bank account with the initial balance
        //     Auth::user()->id,
        //     "Bank Account Created: #" . $request->account_no
        // );

        // Record the initial transaction for the bank account
        Transaction::create([
            'store_id' => $request->store_id,
            'bank_account_id' => $account->id,
            'debit' => 0, // No debit
            'credit' => $request->initial_balance ?? 0, // Credit equals initial balance
            'balance' => $account->current_balance, // Match the bank account's current_balance
            'created_by' => Auth::id(),
            'note' => "Initial balance added to bank account: #" . $request->account_no,
        ]);

        return redirect()
            ->route('accounts.create')
            ->with('flash_success', $this->toastMessage('Bank account successfully added.'));
    }

    public function edit(Request $request, $id)
    {
        $account = BankAccount::findOrFail($id);
        $stores = Store::get();
        return view("banks.accounts.edit")->with(compact('account', 'stores'));
    }

    public function update(StoreBankAccountRequest $request, $id)
{
    // Find the account by ID
    $account = BankAccount::findOrFail($id);

    // Get the previous balance before updating
    $previous_balance = $account->current_balance;

    // Update the account fields
    $account->bank_name = $request->bank_name;
    $account->account_no = $request->account_no;
    $account->account_type = $request->account_type;
    $account->initial_balance = $request->initial_balance ?? 0;
    $account->store_id = $request->store_id;

    // Save the updated account
    $account->save();

    // Redirect with success message
    return redirect()
        ->route('accounts.index')
        ->with('flash_success', $this->toastMessage('Bank account successfully updated.'));
}



    public function destroy($id)
    {
        if (!empty($id)) {
            $account = BankAccount::findOrFail($id);

            // Record the transaction for deleting the bank account (negative balance to reflect removal)
            Transaction::createTransaction(
                $account->store_id,
                $account->id,
                $account->current_balance, // Debit the current balance on deletion
                0, // No credit when deleting
                Auth::user()->id,
                "Bank Account Deleted: #" . $account->account_no
            );

            // Delete the bank account
            $account->delete();

            return redirect()
                ->route('accounts.index')
                ->with('flash_success', $this->toastMessage('Bank account successfully deleted.', 'warning'));
        }
    }
}
