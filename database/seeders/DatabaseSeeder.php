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
    public function run(): void
    {
        // First, ensure that user roles and permissions are set up
        $this->call(StoreSeeder::class);
        $this->call(UserRolePermissionSeeder::class);

        // Now, we can call the other seeders
        $this->call(SiteInfoSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(SmsSettingsSeeder::class);
        $this->call(RackSeeder::class);
        $this->call(BankAccountSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(PaymentCardTypeSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ProductBrandSeeder::class);
        $this->call(ProductCategorySeeder::class);
    }
}
