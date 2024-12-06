<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\EmployeeSalary;
use App\Models\Employee;
use App\Models\BankAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view employee salary')->only('index','show');
        $this->middleware('permission:create employee salary')->only('create', 'store');
        $this->middleware('permission:update employee salary')->only('edit', 'update');
        $this->middleware('permission:delete employee salary')->only('destroy');
    }
    public function index(){
        $salary_pays = EmployeeSalary::with('user','bank_account')->orderBy('id','desc')->paginate(100);
        return view("employees.salarypays.index")->with(compact('salary_pays'));
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
            'pay_for_month' => 'required|date_format:Y-m',
        ]);

        if ($validated) {
            $emp = Employee::find($request->employee_id);
            $salary_pay = new EmployeeSalary();
            $salary_pay->store_id = $emp->store_id;
            $salary_pay->employee_id = $request->employee_id;
            $salary_pay->bank_account_id = $request->bank_account_id;
            $salary_pay->amount = $request->amount;
            $salary_pay->note = $request->note;
            $salary_pay->paid_date = $request->paid_date;
            $salary_pay->pay_for_month = $request->pay_for_month;
            $salary_pay->save();

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
        $salary_pay = EmployeeSalary::FindOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
            'paid_date' => 'required|date',
            'pay_for_month' => 'required|date_format:Y-m',
        ]);

        if ($validated) {
            $emp = Employee::find($request->employee_id);
            $salary_pay->store_id = $emp->store_id;
            $salary_pay->employee_id = $request->employee_id;
            $salary_pay->bank_account_id = $request->bank_account_id;
            $salary_pay->amount = $request->amount;
            $salary_pay->note = $request->note;
            $salary_pay->paid_date = $request->paid_date;
            $salary_pay->pay_for_month = $request->pay_for_month;
            $salary_pay->save();

            return redirect()->route('salarypays.edit', $salary_pay->id)->with('flash_success', '
                <script>
                Toast.fire({
                    icon: `success`,
                    title: `Employee salary pay successfully updated`
                })
                </script>
            ');
        }

        $bank_accounts = BankAccount::with('store')->get();
        $employees = Employee::get();
        return view("employees.salarypays.edit")->with(compact('salary_pay', 'bank_accounts', 'employees'));
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            EmployeeSalary::find($id)->delete();
            return redirect()->back()->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Employe salary pay successfully deleted`
                })
                </script>
                ');
        }
    }
}
