<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuItems = [
            [
                'name' => 'Dashboard',
                'route' => 'dashboard',
                'icon' => 'fas fa-tachometer-alt',
            ],
            [
                'name' => 'New Sale',
                'route' => 'invoices.create',
                'icon' => 'fas fa-shopping-cart',
            ],
            [
                'name' => 'Invoices',
                'route' => 'invoices.index',
                'icon' => 'fas fa-file-invoice',
            ],
            [
                'name' => 'Reports',
                'route' => 'reports.index',
                'icon' => 'fas fa-file-alt',
            ],
            [
                'name' => 'Products',
                'route' => 'products.index',
                'icon' => 'fas fa-box',
            ],
            [
                'name' => 'StockIns',
                'route' => 'stockins.index',
                'icon' => 'fas fa-arrow-down',
            ],
            [
                'name' => 'Stores',
                'route' => 'stores.index',
                'icon' => 'fas fa-store',
            ],
            [
                'name' => 'Suppliers',
                'route' => 'suppliers.index',
                'icon' => 'fas fa-truck',
            ],
            [
                'name' => 'Categories',
                'route' => 'categories.index',
                'icon' => 'fas fa-tags',
            ],
            [
                'name' => 'Brands',
                'route' => 'brands.index',
                'icon' => 'fas fa-tag',
            ],
            [
                'name' => 'Units',
                'route' => 'units.index',
                'icon' => 'fas fa-ruler',
            ],
            [
                'name' => 'Racks',
                'route' => 'racks.index',
                'icon' => 'fas fa-boxes',
            ],
            [
                'name' => 'Users',
                'route' => 'users.index',
                'icon' => 'fas fa-users',
            ],
            [
                'name' => 'Accounts',
                'route' => 'accounts.index',
                'icon' => 'fas fa-user-circle',
            ],
            [
                'name' => 'Expenses',
                'route' => 'expenses.index',
                'icon' => 'fas fa-file-invoice-dollar',
            ],
            [
                'name' => 'Assets',
                'route' => 'assets.index',
                'icon' => 'fas fa-archive',
            ],
        ];

        foreach ($menuItems as $menuItem) {
            DB::table('menus')->insert([
                'name' => $menuItem['name'],
                'route' => $menuItem['route'],
                'icon' => $menuItem['icon'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
