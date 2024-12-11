<?php

namespace App\Http\Controllers\Backend;
use Auth;
use App\Models\Transaction;
use App\Models\Store;
use App\Models\Expense;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view expense')->only('index','show');
        $this->middleware('permission:create expense')->only('create', 'store');
        $this->middleware('permission:update expense')->only('edit', 'update');
        $this->middleware('permission:delete expense')->only('destroy');
    }
    public function create()
    {
        $stores = Auth::user()->role == 'station' 
            ? Store::where('id', Auth::user()->store_id)->get() 
            : Store::all();

        $bank_accounts = BankAccount::with('store')
            ->when(Auth::user()->role == 'station', function ($query) {
                $query->where('store_id', Auth::user()->store_id);
            })
            ->get();

        return view("expenses.create", compact('bank_accounts', 'stores'));
    }


    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validation rules
            $request->validate([
                'store_id' => 'required|exists:stores,id',
                'bank_account_id' => 'required|exists:bank_accounts,id',
                'amount' => 'required|numeric|min:0.01',
                'expense_type' => 'required|string|max:255',
                'expense_date' => 'required|date',
            ]);


            // Save the expense record
            $expense = new Expense();
            $expense->store_id = $request->store_id;
            $expense->bank_account_id = $request->bank_account_id;
            $expense->amount = $request->amount;
            $expense->expense_type = $request->expense_type;
            $expense->expense_date = $request->expense_date;
            $expense->save();

            // Record the transaction
            Transaction::createTransaction(
                $request->store_id,
                $request->bank_account_id,
                $request->amount, // Debit the expense amount
                0, // No credit
                Auth::user()->id,
                "Expense: {$expense->expense_type}, Amount: {$request->amount}"
            );

            // Redirect with success message
            return redirect()->route('expenses.create')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: `success`,
                        title: `Expense successfully added`
                    })
                </script>
            ');
        }
    }


    public function index(){
        $expenses = Expense::with('store','bank_account')->orderBy('id')->paginate(100);
        return view("expenses.index")->with(compact('expenses'));
    }
    public function edit($id)
    {
        // Find the expense by ID
        $expense = Expense::findOrFail($id);
        $stores = Auth::user()->role == 'station' 
            ? Store::where('id', Auth::user()->store_id)->get() 
            : Store::all();

        $bank_accounts = BankAccount::with('store')
            ->when(Auth::user()->role == 'station', function ($query) {
                $query->where('store_id', Auth::user()->store_id);
            })
            ->get();
        return view("expenses.edit")->with(compact('expense','bank_accounts','stores'));
    }
    
    public function update(Request $request, $id)
    {
        // Find the expense by ID
        $expense = Expense::findOrFail($id);

        // Validation rules
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_type' => 'required|string|max:255',
            'expense_date' => 'required|date',
        ]);

        $oldAmount = $expense->amount;

        // Update the expense fields
        $expense->store_id = $request->store_id;
        $expense->bank_account_id = $request->bank_account_id;
        $expense->amount = $request->amount;
        $expense->expense_type = $request->expense_type;
        $expense->expense_date = $request->expense_date;
        $expense->save();

        // Calculate the balance adjustment
        $balanceDifference = $oldAmount - $request->amount;

        

        // Record the transaction
        if ($balanceDifference != 0) {
            Transaction::createTransaction(
                $request->store_id,
                $request->bank_account_id,
                $balanceDifference < 0 ? abs($balanceDifference) : 0, // Debit if decreasing
                $balanceDifference > 0 ? abs($balanceDifference) : 0, // Credit if increasing
                Auth::user()->id,
                "Expense Updated: {$expense->expense_type}, Adjusted Amount: {$balanceDifference}"
            );
        }

        // Redirect with success message
        return redirect()->route('expenses.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Expense successfully updated`
                })
            </script>
        ');
    }


    public function destroy($id)
    {
        // Find the expense by ID
        $expense = Expense::findOrFail($id);

        // Record the transaction
        Transaction::createTransaction(
            $expense->store_id,
            $expense->bank_account_id,
            0, // No debit as we are refunding the amount
            $expense->amount, // Credit the bank account with the refunded amount
            Auth::user()->id,
            "Expense Deleted: {$expense->expense_type}, Refunded Amount: {$expense->amount}"
        );

        // Delete the expense record
        $expense->delete();

        // Redirect with success message
        return redirect()->route('expenses.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Expense successfully deleted`
                })
            </script>
        ');
    }

}
