<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Add permissions here
        Permission::firstOrCreate(['name' => 'view product']);
        Permission::firstOrCreate(['name' => 'create product']);
        Permission::firstOrCreate(['name' => 'update product']);
        Permission::firstOrCreate(['name' => 'delete product']);
    }
}
