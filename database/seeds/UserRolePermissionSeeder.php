<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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
                    'email' => 'superadmin@gmail.com',
                    'password' => Hash::make ('12345678'),
                ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $adminUser->assignRole($adminRole);

        $stationUser = User::firstOrCreate([
                            'email' => 'station@gmail.com'
                        ], [
                            'name' => 'Station',
                            'email' => 'station@gmail.com',
                            'password' => Hash::make ('12345678'),
                        ]);

        $stationUser->assignRole($stationRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('12345678'),
                        ]);

        $staffUser->assignRole($staffRole);
    }
}
