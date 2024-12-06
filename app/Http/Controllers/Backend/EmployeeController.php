<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\Store;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageUploadHelper;
use Illuminate\Database\QueryException;
use Auth;
use Image;

class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view employee')->only('index','show');
        $this->middleware('permission:create employee')->only('create', 'store');
        $this->middleware('permission:update employee')->only('edit', 'update');
        $this->middleware('permission:delete employee')->only('destroy');
    }

    public function index()
    {
        $employees = Employee::orderBy('id')->paginate(100);
        return view("employees.index")->with(compact('employees'));
    }

    public function create()
    {
        $stores = Store::get();
        $roles = Role::get();
        return view('employees.create')->with(compact('stores', 'roles'));
    }

    public function store(Request $request)
{
    // Validate form data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => ['required', 'regex:/^\+?[0-9\s\-]{7,15}$/', 'unique:employees,phone'],
        'email' => 'required|email|max:255|unique:users,email',
        'date_of_birth' => 'required|date',
        'nid' => 'required|string|max:20|unique:employees,nid',
        'blood_group' => ['required', 'regex:/^(A|B|AB|O)[+-]$/'],
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'store_id' => 'required|integer|exists:stores,id',
        'role' => 'required|string',
        'job_title' => 'required|string|max:255',
        'join_date' => 'required|date',
        'salary' => 'required|numeric',
    ]);

    // Start database transaction
    DB::beginTransaction();

    try {
        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageUploadHelper::uploadImage($request, 'image', 'employees', 300, 300);
        }

        // Create Employee record
        Employee::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'store_id' => $validatedData['store_id'],
            'role' => $validatedData['role'],
            'email' => $validatedData['email'],
            'job_title' => $validatedData['job_title'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'join_date' => $validatedData['join_date'],
            'salary' => $validatedData['salary'],
            'blood_group' => $validatedData['blood_group'],
            'nid' => $validatedData['nid'],
            'image' => $imagePath,
        ]);

        // Commit the transaction
        DB::commit();

        // Redirect with success message
        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    } catch (QueryException $e) {
        // Rollback transaction on error
        DB::rollBack();

        // Log the query exception for further investigation
        \Log::error('QueryException: ' . $e->getMessage());

        // Handle specific error codes (e.g., unique constraint violation)
        if ($e->errorInfo[1] == 1062) {
            return back()->withErrors(['phone' => 'The phone number or other unique value is already in use.'])->withInput();
        }

        // General error message for other cases
        return back()->withErrors(['error' => 'Failed to create the employee. Please try again.'])->withInput();
    } catch (\Exception $e) {
        // Rollback transaction on unexpected error
        DB::rollBack();

        // Log the general exception for further investigation
        \Log::error('Exception: ' . $e->getMessage());

        // General error message
        return back()->withErrors(['error' => 'An unexpected error occurred. Please try again.'])->withInput();
    }
}




    public function edit(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $stores = Store::get();
        $roles = Role::get();
        return view("employees.edit")->with(compact('stores', 'employee', 'roles'));
    }

    public function update(Request $request, $id)
{
    try {
        // Find the employee by ID or fail
        $employee = Employee::findOrFail($id);

        // Validate form data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'required',
                'regex:/^\+?[0-9\s\-]{7,15}$/',
                'unique:employees,phone,' . $id, // Ignore current user's phone for uniqueness
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:employees,email,' . $id, // Ignore current user's email for uniqueness
            ],
            'date_of_birth' => 'required|date',
            'nid' => [
                'required',
                'string',
                'max:20',
                'unique:employees,nid,' . $id, // Ignore current user's NID for uniqueness
            ],
            'blood_group' => ['required', 'regex:/^(A|B|AB|O)[+-]$/'],
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'store_id' => 'required|integer|exists:stores,id',
            'role' => 'required|string',
            'job_title' => 'required|string|max:255',
            'join_date' => 'required|date',
            'salary' => 'required|numeric|min:0',
        ]);

        // Handle image upload if provided
        $imagePath = $employee->img; // Default to old image if no new one is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($employee->img) {
                ImageUploadHelper::deleteImage($employee->img, 'employees');
            }

            // Upload the new image
            $imagePath = ImageUploadHelper::uploadImage($request, 'image', 'employees', 300, 300);
        }

        // Check if a new password is provided
        $password = $request->filled('password') ? bcrypt($request->input('password')) : $employee->password;

        // Prepare employee data for update
        $employeeData = [
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'store_id' => $validatedData['store_id'],
            'role' => $validatedData['role'],
            'email' => $validatedData['email'],
            'job_title' => $validatedData['job_title'],
            'join_date' => $validatedData['join_date'],
            'salary' => $validatedData['salary'],
            'blood_group' => $validatedData['blood_group'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'nid' => $validatedData['nid'],
            'image' => $imagePath,
            'password' => $password, // Include the password if it's provided
        ];

        // Update employee data
        $employee->update($employeeData);

        // Redirect with success message
        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');

    } catch (\Exception $e) {
        // Log the error with detailed exception message and line number
        \Log::error('Employee update failed at line ' . $e->getLine() . ': ' . $e->getMessage());
        
        // Return back with error message and display exception message for debugging (remove in production)
        return back()->withErrors(['error' => 'An error occurred while updating the employee: ' . $e->getMessage()]);
    }
}


    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            // Delete the user's image if it exists
            if ($employee->img) {
                ImageUploadHelper::deleteImage($employee->img, 'employees');
            }
            $employee->delete();

            return back()->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete the employee. Please try again.']);
        }
    }
}
