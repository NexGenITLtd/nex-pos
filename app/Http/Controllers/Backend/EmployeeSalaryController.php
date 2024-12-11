<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\EmployeeSalary;
use App\Models\Employee;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view employee-salary')->only('index','show');
        $this->middleware('permission:create employee-salary')->only('create', 'store');
        $this->middleware('permission:update employee-salary')->only('edit', 'update');
        $this->middleware('permission:delete employee-salary')->only('destroy');
    }
    public function index(){
        $salary_pays = EmployeeSalary::with('user','bank_account')->orderBy('id','desc')->paginate(100);
        return view("employees.salarypays.index")->with(compact('salary_pays'));
    }
    public function show($id)
    {
        return $salary_pay = EmployeeSalary::findOrFail($id);
    }
    public function create()
    {
        $bank_accounts = BankAccount::with('store')->get();
        $employees = Employee::get();
        return view('employees.salarypays.create')->with(compact('bank_accounts','employees'));
    }
    public function store(Request $request)
{
    // Validation rules
    $validated = $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'bank_account_id' => 'required|exists:bank_accounts,id',
        'amount' => 'required|numeric|min:0',
        'note' => 'nullable|string|max:255',
        'paid_date' => 'required|date',
        'pay_for_month' => 'required|date_format:Y-m-d',
    ]);

    if ($validated) {
        $emp = Employee::find($request->employee_id);
        $bankAccount = BankAccount::find($request->bank_account_id);

        // Check if employee and bank account are valid
        if (!$emp || !$bankAccount) {
            return redirect()->route('salarypays.create')->with('flash_error', 'Invalid employee or bank account.');
        }

        // Create the salary pay record
        $salary_pay = new EmployeeSalary();
        $salary_pay->store_id = $emp->store_id;
        $salary_pay->employee_id = $request->employee_id;
        $salary_pay->bank_account_id = $request->bank_account_id;
        $salary_pay->amount = $request->amount;
        $salary_pay->note = $request->note;
        $salary_pay->paid_date = $request->paid_date;
        $salary_pay->pay_for_month = $request->pay_for_month;
        $salary_pay->save();

        // Record the transaction (debit the bank account without changing the balance)
        Transaction::createTransaction(
            $emp->store_id, 
            $request->bank_account_id, 
            $request->amount, // Debit the bank account
            0, // No credit (assuming no money is being credited here)
            Auth::user()->id, // Logged-in user
            "Employee Salary Payment: Employee ID {$emp->id}, Amount: {$request->amount}, Pay for Month: {$request->pay_for_month}"
        );

        return redirect()->route('salarypays.create')->with('flash_success', '
            <script>
            Toast.fire({
                icon: `success`,
                title: `Employee salary pay successfully added`
            })
            </script>
        ');
    }
}


    public function edit(Request $request, $id){
        $salary_pay = EmployeeSalary::FindOrFail($id);
        $bank_accounts = BankAccount::with('store')->get();
        $employees = Employee::get();
        return view("employees.salarypays.edit")->with(compact('salary_pay','bank_accounts','employees'));
    }
    

    public function update(Request $request, $id)
    {
        $salary_pay = EmployeeSalary::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
            'paid_date' => 'required|date',
            'pay_for_month' => 'required|date_format:Y-m-d',
        ]);

        if ($validated) {
            // Get the employee and bank account
            $emp = Employee::find($request->employee_id);
            $bankAccount = BankAccount::find($request->bank_account_id);

            // Capture the old salary amount
            $oldAmount = $salary_pay->amount;

            // Update the salary payment
            $salary_pay->store_id = $emp->store_id;
            $salary_pay->employee_id = $request->employee_id;
            $salary_pay->bank_account_id = $request->bank_account_id;
            $salary_pay->amount = $request->amount;
            $salary_pay->note = $request->note;
            $salary_pay->paid_date = $request->paid_date;
            $salary_pay->pay_for_month = $request->pay_for_month;
            $salary_pay->save();

            // Calculate the balance difference
            $balanceDifference = $request->amount - $oldAmount;

            // Only create the transaction if the amount has changed
            if ($balanceDifference != 0) {
                // Record the transaction
                Transaction::createTransaction(
                    $emp->store_id,
                    $request->bank_account_id,
                    $balanceDifference > 0 ? abs($balanceDifference) : 0, // Debit if decreasing
                    $balanceDifference < 0 ? abs($balanceDifference) : 0, // Credit if increasing
                    Auth::user()->id, // Logged-in user
                    "Employee Salary Updated: Employee ID {$emp->id}, Adjusted Amount: {$balanceDifference}, Pay for Month: {$request->pay_for_month}"
                );
            }

            return redirect()->route('salarypays.edit', $salary_pay->id)->with('flash_success', '
                <script>
                Toast.fire({
                    icon: `success`,
                    title: `Employee salary pay successfully updated`
                })
                </script>
            ');
        }

        // Get bank accounts and employees for the form
        $bank_accounts = BankAccount::with('store')->get();
        $employees = Employee::get();

        return view("employees.salarypays.edit", compact('salary_pay', 'bank_accounts', 'employees'));
    }


    public function destroy($id)
    {
        // Ensure the ID is not empty
        if (!empty($id)) {
            // Retrieve the salary pay record
            $salaryPay = EmployeeSalary::findOrFail($id);

            // Capture the necessary details
            $amount = $salaryPay->amount;
            $bankAccountId = $salaryPay->bank_account_id;
            $storeId = $salaryPay->store_id;
            $employeeId = $salaryPay->employee_id;

            // Create a transaction for the reversal of the salary payment
            Transaction::createTransaction(
                $storeId,
                $bankAccountId,
                0, // No debit as it was already paid
                $amount, // Credit the bank account to reverse the payment
                Auth::user()->id, // The current logged-in user
                "Deleted Salary Payment: Employee ID {$employeeId}, Reversed Amount: {$amount}"
            );

            // Delete the salary pay record
            $salaryPay->delete();

            // Redirect with success message
            return redirect()->route('salarypays.index')->with('flash_success', '
                <script>
                Toast.fire({
                    icon: `success`,
                    title: `Employee salary pay successfully deleted`
                })
                </script>
            ');
        }
    }

}
