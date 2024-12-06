<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view menu')->only('index','show');
        $this->middleware('permission:create menu')->only('create', 'store', 'insertMenus');
        $this->middleware('permission:update menu')->only('edit', 'update','sellProductUpdate','sellProductUpdateQty','sellProductDelete');
        $this->middleware('permission:delete menu')->only('destroy');
    }

    public function getRoleBasedMenu()
    {
        // Get the current logged-in user's roles
        // $userRoles = Auth::user()->roles->pluck('id')->toArray(); // Assuming user has many roles

        // // Fetch menus that are allowed for the user's roles
        // $menuItems = Menu::with('children')->orderBy('order', 'asc')
        // ->get();

        // return view('inc.sidebar', compact('menuItems'));
    }

	public function insertMenus()
    {
        $menuItems = [
            ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-tachometer-alt'],
            ['name' => 'New Sale', 'route' => 'invoices.create', 'icon' => 'fas fa-shopping-cart'],
            ['name' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fas fa-file-invoice'],
            ['name' => 'Reports', 'route' => 'reports.index', 'icon' => 'fas fa-file-alt'],
            ['name' => 'Products', 'route' => 'products.index', 'icon' => 'fas fa-box'],
            ['name' => 'StockIns', 'route' => 'stockins.index', 'icon' => 'fas fa-arrow-down'],
            ['name' => 'Stores', 'route' => 'stores.index', 'icon' => 'fas fa-store'],
            ['name' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Categories', 'route' => 'categories.index', 'icon' => 'fas fa-tags'],
            ['name' => 'Brands', 'route' => 'brands.index', 'icon' => 'fas fa-tag'],
            ['name' => 'Units', 'route' => 'units.index', 'icon' => 'fas fa-ruler'],
            ['name' => 'Racks', 'route' => 'racks.index', 'icon' => 'fas fa-boxes'],
            ['name' => 'Users', 'route' => 'users.index', 'icon' => 'fas fa-users'],
            ['name' => 'Accounts', 'route' => 'accounts.index', 'icon' => 'fas fa-user-circle'],
            ['name' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fas fa-file-invoice-dollar'],
            ['name' => 'Assets', 'route' => 'assets.index', 'icon' => 'fas fa-archive'],
        ];

        foreach ($menuItems as $item) {
            // Create the menu item if it doesn't exist
            $menu = Menu::firstOrCreate(['name' => $item['name']], $item);

            // Create or check for permission based on the route
            Permission::firstOrCreate(['name' => 'view-menu-' . strtolower(str_replace(' ', '_', $item['name']))]);

            // Optionally assign the created permission to the menu item
            // Example: Assigning 'view-menu-[menu_name]' permission to the menu item or a role
        }
        return "Menus inserted successfully!";

        return redirect()->back()->with('flash_success',"
                <script>
                toastr.success('Menus inserted successfully!');
                </script>
                ");
        // return response()->json(['message' => 'Menus inserted successfully!']);
    }
    // Display a list of menus
    public function index()
    {
        
        $menus = Menu::with('children')->whereNull('parent_id')->orderBy('order')->paginate(200);
        return view('menus.index', compact('menus'));
    }

    // Show the form for creating a new menu
    public function create()
    {
        // Get all the routes, map to their names, and filter out any unnamed routes
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();  // Get the route name
        })->filter()->values();  // Filter out null or empty names, and reset the array keys

        // Fetch parent menus for the dropdown
        $parentMenus = Menu::whereNull('parent_id')->get();


        // Pass the data to the view
        return view('menus.create', compact('parentMenus', 'routes'));
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:menus,id',
            'order' => 'nullable|integer',
        ]);

        // Store the new menu item
        $menu = Menu::create($validatedData);

        // Create a permission based on the route
        $permissionName = 'view-menu-' . strtolower(str_replace(' ', '_', $menu->name));
        $permission = Permission::firstOrCreate(['name' => $permissionName]);

        // Optionally assign the permission to the 'admin' role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }

        // Redirect back with success message
        return redirect()->route('menus.index')->with('flash_success', "
            <script>
            toastr.success('Menu created and permission assigned successfully.');
            </script>
        ");
    }


    // Show the form for editing a specific menu
    public function edit(Menu $menu)
    {
        // Get all the routes, map to their names, and filter out any unnamed routes
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return $route->getName();  // Get the route name
        })->filter()->values();  // Filter out null or empty names, and reset the array keys

        // Fetch parent menus for the dropdown
        $parentMenus = Menu::whereNull('parent_id')->get();

        // Debugging: Uncomment to check if the routes are being retrieved
        // dd($routes); 
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('menus.edit', compact('menu', 'parentMenus', 'routes'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string',
            'route' => 'nullable|string',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id',
            'order' => 'nullable|integer',
        ]);

        // Save the old permission name to delete it later
        $oldPermissionName = 'view-menu-' . strtolower(str_replace(' ', '_', $menu->name));

        // Update the menu item
        $menu->update($request->all());

        // Create the new permission name
        $newPermissionName = 'view-menu-' . strtolower(str_replace(' ', '_', $menu->name));

        // Update the permission if the name has changed
        if ($oldPermissionName !== $newPermissionName) {
            // Delete the old permission
            $oldPermission = Permission::where('name', $oldPermissionName)->first();
            if ($oldPermission) {
                $oldPermission->delete();
            }

            // Create the new permission
            $permission = Permission::firstOrCreate(['name' => $newPermissionName]);

            // Optionally assign the new permission to the 'admin' role
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $adminRole->givePermissionTo($permission);
            }
        }

        // Redirect back with success message
        return redirect()->route('menus.index')->with('flash_success', "
            <script>
            toastr.success('Menu updated and permission name updated successfully.');
            </script>
        ");
    }


    public function destroy(Menu $menu)
    {
        // Get the permission name based on the menu's name
        $permissionName = 'view-menu-' . strtolower(str_replace(' ', '_', $menu->name));

        // Check if the permission exists
        $permission = Permission::where('name', $permissionName)->first();
        
        if ($permission) {
            // Detach the permission from any roles it might be assigned to
            $permission->roles()->detach();
            
            // Delete the permission
            $permission->delete();
        }

        // Delete the menu item
        $menu->delete();

        // Redirect back with success message
        return redirect()->route('menus.index')->with('flash_success', "
            <script>
            toastr.danger('Menu and its permission deleted successfully.');
            </script>
        ");
    }

}

