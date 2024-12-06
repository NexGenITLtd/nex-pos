<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\RoleMenu;
// use App\Models\Role;
use App\Models\Menu;




use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleMenuController extends Controller
{
    public function insertRoles()
    {
        // Create Permissions
        Permission::firstOrCreate(['name' => 'view role']);
        Permission::firstOrCreate(['name' => 'create role']);
        Permission::firstOrCreate(['name' => 'update role']);
        Permission::firstOrCreate(['name' => 'delete role']);

        Permission::firstOrCreate(['name' => 'view permission']);
        Permission::firstOrCreate(['name' => 'create permission']);
        Permission::firstOrCreate(['name' => 'update permission']);
        Permission::firstOrCreate(['name' => 'delete permission']);

        Permission::firstOrCreate(['name' => 'view user']);
        Permission::firstOrCreate(['name' => 'create user']);
        Permission::firstOrCreate(['name' => 'update user']);
        Permission::firstOrCreate(['name' => 'delete user']);

        Permission::firstOrCreate(['name' => 'view product']);
        Permission::firstOrCreate(['name' => 'create product']);
        Permission::firstOrCreate(['name' => 'update product']);
        Permission::firstOrCreate(['name' => 'delete product']);


        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']); //as super-admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $stationRole = Role::firstOrCreate(['name' => 'station']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Lets give all permission to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $superAdminRole->givePermissionTo($allPermissionNames);

        // Let's give few permissions to admin role.
        $adminRole->givePermissionTo(['create role', 'view role', 'update role']);
        $adminRole->givePermissionTo(['create permission', 'view permission']);
        $adminRole->givePermissionTo(['create user', 'view user', 'update user']);
        $adminRole->givePermissionTo(['create product', 'view product', 'update product']);


        // Let's Create User and assign Role to it.

        $superAdminUser = User::firstOrCreate([
                    'email' => 'superadmin@gmail.com',
                ], [
                    'name' => 'Super Admin',
                    // 'role_id' => 1,
                    // 'role' => 'super-admin',
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('12345678'),
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            // 'role_id' => 2,
                            // 'role' => 'admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);

        $stationUser = User::firstOrCreate([
                            'email' => 'station@gmail.com'
                        ], [
                            'name' => 'Station',
                            // 'role_id' => 3,
                            // 'role' => 'station',
                            'email' => 'station@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $stationUser->assignRole($stationRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            // 'role_id' => 4,
                            // 'role' => 'staff',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('12345678'),
                        ]);

        $staffUser->assignRole($staffRole);
        
        return response()->json(['message' => 'Role  & Permission inserted successfully!']);
    }
	public function assignMenusToAdmin()
	{
	    // Retrieve the admin role
	    $adminRole = Role::where('name', 'admin')->first();

	    if ($adminRole) {
	        // Retrieve all menus
	        $menus = Menu::all();

	        foreach ($menus as $menu) {
	            // Check if the RoleMenu entry already exists
	            if (!RoleMenu::where('role_id', $adminRole->id)->where('menu_id', $menu->id)->exists()) {
	                // Create RoleMenu if it does not exist
	                RoleMenu::create([
	                    'role_id' => $adminRole->id,
	                    'menu_id' => $menu->id,
	                    'can_create' => true,  // Set the permission for create
	                    'can_edit' => true,    // Set the permission for edit
                        'can_delete' => true,  // Set the permission for delete
	                    'can_view' => true,  // Set the permission for view
	                ]);
	            }
	        }
            return redirect()->route('role-menus.index')->with('flash_success',"
                <script>
                toastr.success('All menus assigned to Admin role.');
                </script>
                ");
	        // return redirect()->route('role-menus.index')->with('success', 'All menus assigned to Admin role.');
	    }
        return redirect()->route('role-menus.index')->with('flash_success',"
                <script>
                toastr.error('Admin role not found.');
                </script>
                ");
	    // return redirect()->route('role-menus.index')->with('error', 'Admin role not found.');
	}


    public function index()
    {
        $roles = Role::all();
        $menus = Menu::all();
        $roleMenus = RoleMenu::with(['role', 'menu'])->get(); // Ensure you have relations set

        return view('role_menus.index', compact('roles', 'menus', 'roleMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|array',
            'role_id.*' => 'exists:roles,id',
            'menu_id' => 'required|array',
            'menu_id.*' => 'exists:menus,id',
            'can_create' => 'boolean',
            'can_edit' => 'boolean',
            'can_delete' => 'boolean',
            'can_view' => 'boolean',
        ]);

        // Loop through each selected role and menu and create or update them
        foreach ($request->role_id as $roleId) {
            foreach ($request->menu_id as $menuId) {
                $roleMenu = RoleMenu::updateOrCreate(
                    [
                        'role_id' => $roleId,
                        'menu_id' => $menuId,
                        'id' => $request->id // If you are updating, this should reference the existing ID
                    ],
                    $request->only('can_create', 'can_edit', 'can_delete'), 'can_view' // Only save the permissions
                );
            }
        }

        return response()->json(['message' => 'Role menus saved successfully!', 'data' => $roleMenu], 200);
    }


    public function destroy($id)
    {
        $roleMenu = RoleMenu::findOrFail($id);
        $roleMenu->delete();

        return response()->json(['message' => 'Role menu deleted successfully!']);
    }
}

