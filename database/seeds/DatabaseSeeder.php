<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // First, ensure that user roles and permissions are set up
        $this->call(UserRolePermissionSeeder::class);

        // Now, we can call the other seeders
        $this->call(SiteInfoSeeder::class);
        $this->call(MenuSeeder::class);
    }
}
