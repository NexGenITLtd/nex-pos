<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
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

            $expense = new Expense();
            $expense->store_id = $request->store_id;
            $expense->bank_account_id = $request->bank_account_id;
            $expense->amount = $request->amount;
            $expense->expense_type = $request->expense_type;
            $expense->expense_date = $request->expense_date;
            $expense->save();

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
        $expense = Expense::findOrFail($id);

        // Validation rules
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_type' => 'required|string|max:255',
            'expense_date' => 'required|date',
        ]);

        $expense->store_id = $request->store_id;
        $expense->bank_account_id = $request->bank_account_id;
        $expense->amount = $request->amount;
        $expense->expense_type = $request->expense_type;
        $expense->expense_date = $request->expense_date;
        $expense->save();

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
        if (!empty($id)) {
            Expense::find($id)->delete();
            return redirect()->route('expenses.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Expense successfully deleted`
                })
                </script>
                ');
        }
    }
}
