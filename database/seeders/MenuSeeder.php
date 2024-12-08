<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $menuItems = [
            ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-tachometer-alt'],
            ['name' => 'New Sale', 'route' => 'invoices.create', 'icon' => 'fas fa-shopping-cart'],
            ['name' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fas fa-file-invoice'],
            ['name' => 'Reports', 'route' => 'reports.index', 'icon' => 'fas fa-file-alt'],
            ['name' => 'Daily Reports', 'route' => 'dailyreports.index', 'icon' => 'fas fa-file-alt'],
            ['name' => 'Profit Reports', 'route' => 'profit.report', 'icon' => 'fas fa-file-alt'],
            ['name' => 'Supplier Reports', 'route' => 'supplier.report', 'icon' => 'fas fa-file-alt'],
            ['name' => 'Products', 'route' => 'products.index', 'icon' => 'fas fa-box'],
            ['name' => 'StockIns', 'route' => 'product-direct-stock-ins', 'icon' => 'fas fa-arrow-down'],
            ['name' => 'Bulk StockIns', 'route' => 'stockins.index', 'icon' => 'fas fa-arrow-down'],
            ['name' => 'Sell Products', 'route' => 'sell-products.index', 'icon' => 'fas fa-box'],
            ['name' => 'Return Products', 'route' => 'return-sell-products.index', 'icon' => 'fas fa-box'],
            ['name' => 'Stores', 'route' => 'stores.index', 'icon' => 'fas fa-store'],
            ['name' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Supplier Payments', 'route' => 'customer-payments.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Supplier Payment Alerts', 'route' => 'customer-payments.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Customers', 'route' => 'customers.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Customer Payments', 'route' => 'customer-payments.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Categories', 'route' => 'categories.index', 'icon' => 'fas fa-tags'],
            ['name' => 'Brands', 'route' => 'brands.index', 'icon' => 'fas fa-tag'],
            ['name' => 'Units', 'route' => 'units.index', 'icon' => 'fas fa-ruler'],
            ['name' => 'Racks', 'route' => 'racks.index', 'icon' => 'fas fa-boxes'],
            ['name' => 'Roles', 'route' => 'roles.index', 'icon' => 'fas fa-user-shield'],
            ['name' => 'Permissions', 'route' => 'permissions.index', 'icon' => 'fas fa-key'],
            ['name' => 'Menus', 'route' => 'menus.index', 'icon' => 'fas fa-key'],
            ['name' => 'Users', 'route' => 'users.index', 'icon' => 'fas fa-users'],
            ['name' => 'Employees', 'route' => 'employees.index', 'icon' => 'fas fa-users'],
            ['name' => 'Employees Salary', 'route' => 'salarypays.index', 'icon' => 'fas fa-users'],
            ['name' => 'Phone Numbers', 'route' => 'user-phone-data.index', 'icon' => 'fas fa-users'],
            ['name' => 'Accounts', 'route' => 'accounts.index', 'icon' => 'fas fa-user-circle'],
            ['name' => 'Cards', 'route' => 'cards.index', 'icon' => 'fas fa-user-circle'],
            ['name' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fas fa-file-invoice-dollar'],
            ['name' => 'Assets', 'route' => 'assets.index', 'icon' => 'fas fa-archive'],
        ];

        foreach ($menuItems as $item) {
            // Create the menu item if it doesn't exist
            $menu = Menu::firstOrCreate(['name' => $item['name']], $item);

            // Create or check for permission based on the route
            $permissionName = 'view-menu-' . strtolower(str_replace(' ', '_', $item['name']));
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Assign all permissions to the Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']); // Ensure the role exists
        $allPermissions = Permission::all(); // Get all permissions
        $superAdminRole->syncPermissions($allPermissions); // Assign all permissions to the role
    }
}
