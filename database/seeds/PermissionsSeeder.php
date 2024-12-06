<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Add permissions here
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'delete users']);
    }
}
