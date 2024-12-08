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
        $this->middleware('permission:create menu')->only('create', 'store');
        $this->middleware('permission:update menu')->only('edit', 'update','updateOrder');
        $this->middleware('permission:delete menu')->only('destroy');
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

    public function updateOrder(Request $request)
    {
        $orderedIds = $request->input('ordered_ids');

        foreach ($orderedIds as $index => $menuId) {
            $menu = Menu::findOrFail(str_replace('menu-', '', $menuId)); // Extract the ID from the row ID
            $menu->order = $index + 1; // Update the order (index starts from 0, so add 1)
            $menu->save();
        }

        return response()->json(['message' => 'Order updated successfully']);
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

