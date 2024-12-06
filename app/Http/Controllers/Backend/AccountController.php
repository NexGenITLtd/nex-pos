<?php
namespace App\Http\Controllers\Backend;

use App\Http\Requests\StoreBankAccountRequest;
use App\Models\BankAccount;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view bank')->only('index');
        $this->middleware('permission:create bank')->only(['create', 'store']);
        $this->middleware('permission:update bank')->only(['edit', 'update']);
        $this->middleware('permission:delete bank')->only('destroy');
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

    public function store(StoreBankAccountRequest $request) // Use the custom request here
    {
        // If validation passes, create the new bank account
        $account = new BankAccount();
        $account->bank_name = $request->bank_name;
        $account->account_no = $request->account_no;
        $account->account_type = $request->account_type;
        $account->initial_balance = $request->initial_balance ?? 0;
        $account->store_id = $request->store_id;

        // Save the account to the database
        $account->save();

        return redirect()
            ->route('accounts.create')
            ->with('flash_success', $this->toastMessage('Bank account successfully added.'));
    }

    

    public function edit(Request $request, $id)
    {
        $account = BankAccount::FindOrFail($id);
        $stores = Store::get();
        return view("banks.accounts.edit")->with(compact('account', 'stores'));
    }

    public function update(StoreBankAccountRequest $request, $id) // Use the custom request here
    {
        // Find the account by ID
        $account = BankAccount::findOrFail($id);

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
            BankAccount::find($id)->delete();
            return redirect()
                ->route('accounts.index')
                ->with('flash_success', $this->toastMessage('Bank account successfully deleted.','warning'));
        }
    }
}
