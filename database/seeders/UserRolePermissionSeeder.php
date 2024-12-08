<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all model files from the Models directory
        $modelFiles = File::files(app_path('Models'));

        foreach ($modelFiles as $file) {
            // Extract model name from the file
            $modelName = Str::before($file->getFilename(), '.php');

            // Skip non-model classes if any
            if (!class_exists("App\\Models\\$modelName")) {
                continue;
            }

            // Create permissions dynamically for the model with the correct guard name
            foreach (['view', 'create', 'update', 'delete'] as $action) {
                $permissionName = "$action " . Str::kebab($modelName);
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // Explicitly create the "view permission" if needed
        Permission::firstOrCreate(['name' => 'view permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'create permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update permission', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete permission', 'guard_name' => 'web']);
        // dashboard & profit
        Permission::firstOrCreate(['name' => 'show dashboard', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'show profit', 'guard_name' => 'web']);
        // notification
        Permission::firstOrCreate(['name' => 'view notification', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update notification', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'delete notification', 'guard_name' => 'web']);
        // self profile & password
        Permission::firstOrCreate(['name' => 'update self-profile', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'update self-password', 'guard_name' => 'web']);
        // view profit-report
        Permission::firstOrCreate(['name' => 'view profit-report', 'guard_name' => 'web']);

        Permission::firstOrCreate(['name' => 'view supplier-report', 'guard_name' => 'web']);
        // 


        

        // Create Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $stationRole = Role::firstOrCreate(['name' => 'station']);
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Assign all permissions to super-admin
        $allPermissionNames = Permission::pluck('name')->toArray();
        $superAdminRole->syncPermissions($allPermissionNames);

        // Assign specific permissions to the admin role
        $adminRole->givePermissionTo(['create role', 'view role', 'update role', 'view permission']);

        // Create Users and Assign Roles

        $superAdminUser = User::firstOrCreate([
            'email' => 'superadmin@gmail.com',
        ], [
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make ('12345678'),
            'role_id' => 1,
            'store_id' =>1,
        ]);

        $superAdminUser->assignRole($superAdminRole);


        $adminUser = User::firstOrCreate([
                            'email' => 'admin@gmail.com'
                        ], [
                            'name' => 'Admin',
                            'email' => 'admin@gmail.com',
                            'password' => Hash::make ('12345678'),
                            'role_id' => 2,
                            'store_id' =>1,
                        ]);

        $adminUser->assignRole($adminRole);

        $stationUser = User::firstOrCreate([
                            'email' => 'station@gmail.com'
                        ], [
                            'name' => 'Station',
                            'email' => 'station@gmail.com',
                            'password' => Hash::make ('12345678'),
                            'role_id' => 3,
                            'store_id' =>1,
                        ]);

        $stationUser->assignRole($stationRole);


        $staffUser = User::firstOrCreate([
                            'email' => 'staff@gmail.com',
                        ], [
                            'name' => 'Staff',
                            'email' => 'staff@gmail.com',
                            'password' => Hash::make('12345678'),
                            'role_id' => 4,
                            'store_id' =>1,
                        ]);

        $staffUser->assignRole($staffRole);
    }
}
