<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserDataImport;
use Maatwebsite\Excel\HeadingRowImport;

class UserDataController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view user-data', ['only' => ['index']]);
        $this->middleware('permission:create user-data', ['only' => ['create', 'store']]);
        $this->middleware('permission:update user-data', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete user-data', ['only' => ['destroy']]);
    }

    // Show a list of user data (index)
    public function index()
    {
        $userData = UserData::paginate(100);  // Fetch all user data from the database
        return view('user_data.index', compact('userData')); // Return the index view
    }

    // Show form to create a new record (create)
    public function create()
    {
        return view('user_data.create');
    }

    // Store the uploaded file and save data
    public function store(Request $request)
    {
        // Validate the file (xlsx or csv)
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
            'column' => 'required|string',  // Ensure a column is selected
        ]);

        // Get the selected column for phone numbers
        $selectedColumn = $request->input('column');

        // Import the file and pass the selected column
        $file = $request->file('file');
        Excel::import(new UserDataImport($selectedColumn), $file);

        return back()->with('success', 'Data uploaded successfully!');
    }

    // Fetch column names from the uploaded file
    public function getColumns(Request $request)
    {
        try {
            // Validate the file
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv',
            ]);

            // Load the headings from the file
            $headings = (new HeadingRowImport)->toArray($request->file('file'));

            // Check if headings are present
            if (!empty($headings) && isset($headings[0])) {
                return response()->json(['columns' => $headings[0]], 200);  // Return first row as columns
            }

            return response()->json(['columns' => []], 200);  // Return empty array if no headings found
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => 'Failed to extract column names. Please try again.'], 500);
        }
    }

    // Show a specific user data record (show)
    public function show(UserData $userData)
    {
        return view('user_data.show', compact('userData'));
    }

    // Show form to edit a specific user data record (edit)
    public function edit(UserData $userData)
    {
        return view('user_data.edit', compact('userData'));
    }

    // Update a specific user data record (update)
    public function update(Request $request, UserData $userData)
    {
        // Validate phone field with unique validation (excluding the current record's ID)
        $request->validate([
            'phone' => 'required|unique:user_data,phone,' . $userData->id,
        ]);

        // Update the user data record with new data
        $userData->update($request->all());

        return redirect()->route('user-phone-data.index')->with('success', 'User data updated successfully!');
    }

    // Delete a specific user data record (destroy)
    public function destroy(UserData $userData)
    {
        $userData->delete();

        return redirect()->route('user-phone-data.index')->with('success', 'User data deleted successfully!');
    }

    // Delete all user data records (deleteAll)
    public function deleteAll()
    {
        try {
            UserData::truncate(); // Deletes all records in the `user_data` table
            return redirect()->route('user-phone-data.index')->with('success', 'All records deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user-phone-data.index')->with('error', 'Failed to delete records. Please try again.');
        }
    }
}
