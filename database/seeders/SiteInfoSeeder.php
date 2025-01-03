<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteInfo;
use App\Models\User;

class SiteInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Check if the SiteInfo record exists, and create a default one if not
        if (!SiteInfo::first()) {
            // Ensure user with ID 1 exists before creating SiteInfo
            if (!User::find(1)) {
                // If user does not exist, create the user (or you can call a specific seeder here)
                // UserRolePermissionSeeder should create a user, but it's better to ensure its existence here
                $this->command->info('User with ID 1 not found, please run UserRolePermissionSeeder first.');
                return; // Exit early or handle the logic accordingly
            }

            // Create the SiteInfo record with user_id = 1
            SiteInfo::create([
                'name' => 'NexGen IT',
                'phone' => '01731002123',
                'email' => 'nexgenitltd@gmail.com',
                'logo' => 'logo_1733273422.png',
                'print_logo' => 'print_logo_1733273422.png',
                'fav_icon' => 'fav_icon_1733273422.png',
                'short_about' => 'Short about your company.',
                'address' => 'Your Company Address',
                'currency' => 'tk',
                'map_embed' => null,
                'return_policy' => 'Your return policy goes here.',
                'barcode_height' => '100px',
                'barcode_width' => '160px',
                'user_id' => 1,
            ]);
        }
    }
}
