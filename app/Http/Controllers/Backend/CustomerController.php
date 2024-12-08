<?php

namespace App\Http\Controllers\Backend;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageUploadHelper;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;
use Auth;
use Image;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view customer')->only('index','show');
        $this->middleware('permission:create customer')->only('create', 'store');
        $this->middleware('permission:update customer')->only('edit', 'update');
        $this->middleware('permission:delete customer')->only('destroy');
    }
    public function index(){
        $customers = Customer::orderBy('id')->latest()->paginate(1000);
        return view("customers.index")->with(compact('customers'));
    }
    public function create()
    {
        return view("customers.create");
    }
    public function store(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:customers,phone',
            'email' => 'required|email|unique:customers,email',
            'discount' => 'nullable|numeric|min:0|max:100', // Optional, must be a valid number
            'address' => 'nullable|string|max:255', // Optional address field
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file type and size
            'membership' => 'nullable|string', // Can be "Member" or null
        ]);

        // Create the customer record
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->discount = $request->discount;
        $customer->address = $request->address;

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $customer->img = ImageUploadHelper::uploadImage($request, 'image', 'customers', 300, 300);
        }

        // Save "Member" if checked, "Non-member" if not checked
        $customer->membership = $request->has('membership') ? 'Member' : 'Non-member';

        // Save the customer record
        $customer->save();

        // Redirect to the customer index with a success message
        return redirect()->route('customers.index')->with('success', '
            <script>
            Toast.fire({
            icon: `success`,
            title: `Customer registered successfully`
            })
            </script>
        ');
    }

    public function edit(Request $request, $id){
        $customer = Customer::FindOrFail($id);
        return view("customers.edit")->with(compact('customer'));
    }
    
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email,' . $id, // Ensures the email is unique, excluding the current customer
            'discount' => 'nullable|numeric|min:0|max:100', // Optional, must be a valid number
            'address' => 'nullable|string|max:255', // Optional address field
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file type and size
            'membership' => 'nullable|string', // Can be "Member" or null
        ]);

        // Find the customer by ID
        $customer = Customer::findOrFail($id);

        // Update customer details
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->discount = $request->discount;
        $customer->address = $request->address;

        // Handle image upload if provided
        $imagePath = $customer->img; // Default to old image if no new one is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($customer->img) {
                ImageUploadHelper::deleteImage($customer->img, 'customers');
            }

            // Upload the new image
            $imagePath = ImageUploadHelper::uploadImage($request, 'image', 'customers', 300, 300);
        }
        $customer->img = $imagePath;

        // Save "Member" if checked, "Non-member" if not checked
        $customer->membership = $request->has('membership') ? 'Member' : 'Non-member';

        // Save the updated customer record
        $customer->save();

        // Redirect back with success message
        return redirect()->route('customers.index')->with('flash_success', '
            <script>
            Toast.fire({
            icon: `success`,
            title: `Customer successfully updated`
            })
            </script>
        ');
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            $data = Customer::FindOrFail($id);
            if ($data->img) {
                ImageUploadHelper::deleteImage($data->img, 'customers');
            }
            Customer::find($id)->delete();
            return redirect()->route('customers.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Customer successfully deleted`
                })
                </script>
                ');
        }
    }
}
