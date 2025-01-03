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
            ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-home'],
            ['name' => 'New Sale', 'route' => 'invoices.create', 'icon' => 'fas fa-cart-plus'],
            ['name' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fas fa-file-invoice-dollar'],
            ['name' => 'Reports', 'route' => 'reports.index', 'icon' => 'fas fa-chart-line'],
            ['name' => 'Daily Reports', 'route' => 'dailyreports.index', 'icon' => 'fas fa-calendar-day'],
            ['name' => 'Profit Reports', 'route' => 'profit.report', 'icon' => 'fas fa-dollar-sign'],
            ['name' => 'Supplier Reports', 'route' => 'supplier.report', 'icon' => 'fas fa-truck-loading'],
            ['name' => 'Products', 'route' => 'products.index', 'icon' => 'fas fa-cube'],
            ['name' => 'StockIns', 'route' => 'product-direct-stock-ins', 'icon' => 'fas fa-arrow-circle-down'],
            ['name' => 'Bulk StockIns', 'route' => 'stockins.index', 'icon' => 'fas fa-arrow-down'],
            ['name' => 'Sell Products', 'route' => 'sell-products.index', 'icon' => 'fas fa-cash-register'],
            ['name' => 'Return Products', 'route' => 'return-sell-products.index', 'icon' => 'fas fa-undo-alt'],
            ['name' => 'Stores', 'route' => 'stores.index', 'icon' => 'fas fa-store-alt'],
            ['name' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fas fa-truck'],
            ['name' => 'Supplier Payments', 'route' => 'supplier-payments.index', 'icon' => 'fas fa-credit-card'],
            ['name' => 'Supplier Payment Alerts', 'route' => 'supplier-payment-alerts.index', 'icon' => 'fas fa-bell'],
            ['name' => 'Customers', 'route' => 'customers.index', 'icon' => 'fas fa-users'],
            ['name' => 'Customer Payments', 'route' => 'customer-payments.index', 'icon' => 'fas fa-hand-holding-usd'],
            ['name' => 'Categories', 'route' => 'categories.index', 'icon' => 'fas fa-th-large'],
            ['name' => 'Brands', 'route' => 'brands.index', 'icon' => 'fas fa-clipboard-list'],
            ['name' => 'Units', 'route' => 'units.index', 'icon' => 'fas fa-ruler-combined'],
            ['name' => 'Racks', 'route' => 'racks.index', 'icon' => 'fas fa-box-open'],
            ['name' => 'Roles', 'route' => 'roles.index', 'icon' => 'fas fa-user-shield'],
            ['name' => 'Permissions', 'route' => 'permissions.index', 'icon' => 'fas fa-lock'],
            ['name' => 'Menus', 'route' => 'menus.index', 'icon' => 'fas fa-bars'],
            ['name' => 'Users', 'route' => 'users.index', 'icon' => 'fas fa-users-cog'],
            ['name' => 'Employees', 'route' => 'employees.index', 'icon' => 'fas fa-users'],
            ['name' => 'Employees Salary', 'route' => 'salarypays.index', 'icon' => 'fas fa-money-bill'],
            ['name' => 'Phone Numbers', 'route' => 'user-phone-data.index', 'icon' => 'fas fa-phone-alt'],
            ['name' => 'Accounts', 'route' => 'accounts.index', 'icon' => 'fas fa-user-circle'],
            ['name' => 'Cards', 'route' => 'cards.index', 'icon' => 'fas fa-credit-card'],
            ['name' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fas fa-file-invoice-dollar'],
            ['name' => 'Assets', 'route' => 'assets.index', 'icon' => 'fas fa-warehouse'],
            ['name' => 'Sms Settings', 'route' => 'sms-settings.index', 'icon' => 'fas fa-sms'],
            ['name' => 'Owner Transactions', 'route' => 'owner-deposits.index', 'icon' => 'fas fa-wallet'],
            ['name' => 'Transactions', 'route' => 'transactions.index', 'icon' => 'fas fa-exchange-alt'],
            ['name' => 'Sms History', 'route' => 'sms.index', 'icon' => 'fas fa-sms'],
            ['name' => 'Website Setting', 'route' => 'site-info', 'icon' => 'fas fa-cog'],
            
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
