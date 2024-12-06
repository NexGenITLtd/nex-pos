<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Store;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageUploadHelper;
use Illuminate\Database\QueryException;
use Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:update user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }

    public function index()
    {
        $users = User::orderBy('id')->paginate(100);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $stores = Store::all();
        $roles = Role::all();
        return view('users.create', compact('stores', 'roles'));
    }

    public function store(Request $request)
{
    // Validate form data
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => ['required', 'regex:/^\+?[0-9\s\-]{7,15}$/', 'unique:users,phone'],
        'email' => 'required|email|max:255|unique:users,email',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'store_id' => 'required|integer|exists:stores,id',
        'roles' => 'required|array|min:1',
        'roles.*' => 'exists:roles,id', // Validate that roles exist
        'password' => 'required|string|min:6|confirmed',
    ]);

    DB::beginTransaction();
    try {
        // Handle image upload if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = ImageUploadHelper::uploadImage($request, 'image', 'users', 300, 300);
        }

        // Create User record
        $user = User::create([
            'name' => $validatedData['name'],
            'phone' => $validatedData['phone'],
            'email' => $validatedData['email'],
            'img' => $imagePath,
            'store_id' => $validatedData['store_id'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Sync roles only after the user has been created
        $user->roles()->sync($validatedData['roles']);

        DB::commit();

        return redirect()->route('users.index')->with('success', 'Employee created successfully.');
    } catch (QueryException $e) {
        DB::rollBack();
        \Log::error('QueryException: ' . $e->getMessage());

        if ($e->errorInfo[1] == 1062) {
            return back()->withErrors(['phone' => 'The phone number or other unique value is already in use.'])->withInput();
        }

        return back()->withErrors(['error' => 'Failed to create the user. Error: ' . $e->getMessage()])->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Exception: ' . $e->getMessage());

        return back()->withErrors(['error' => 'Failed to create the user. Error: ' . $e->getMessage()])->withInput();
    }
}


    public function edit($id)
    {
        $user = User::findOrFail($id);
        $stores = Store::all();
        $roles = Role::all();
        return view('users.edit', compact('user', 'stores', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|regex:/^\+?[0-9\s\-]{7,15}$/|unique:users,phone,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'store_id' => 'required|exists:stores,id',
            'roles' => 'required|array',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $user->img;
            if ($request->hasFile('image')) {
                if ($user->img) {
                    ImageUploadHelper::deleteImage($user->img, 'users');
                }
                $imagePath = ImageUploadHelper::uploadImage($request, 'image', 'users', 300, 300);
            }

            $password = $request->filled('password') ? Hash::make($request->password) : $user->password;

            $user->update([
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'img' => $imagePath,
                'store_id' => $validatedData['store_id'],
                'password' => $password,
            ]);

            $user->syncRoles($validatedData['roles']);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Exception: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update the user. Please try again.']);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->img) {
                ImageUploadHelper::deleteImage($user->img, 'users');
            }

            $user->delete();

            return back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Delete failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete the user. Please try again.']);
        }
    }
}
