<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // Check if SiteInfo does not exist and user with ID 1 exists
        if (!SiteInfo::first()) {
            // Check if the user with ID 1 exists before creating SiteInfo
            if (!User::find(1)) {
                // If user does not exist, you can either log it, show a warning, or create the user here
                $this->command->info('User with ID 1 does not exist, please ensure user creation.');
                return; // Exit early to avoid creating SiteInfo without a valid user
            }

            // Create a default SiteInfo record
            SiteInfo::create([
                'name' => 'Your Company Name',
                'phone' => '1234567890',
                'email' => 'contact@yourcompany.com',
                'logo' => 'default-logo.png',
                'print_logo' => 'default-print-logo.png',
                'fav_icon' => 'default-favicon.ico',
                'short_about' => 'Short about your company.',
                'address' => 'Your Company Address',
                'currency' => 'USD',
                'map_embed' => '<iframe ...></iframe>',
                'return_policy' => 'Your return policy goes here.',
                'barcode_height' => '1in',
                'barcode_width' => '1.5in',
                'user_id' => 1, // Ensure this user ID exists
            ]);
        }

        // Share the SiteInfo data with all views
        $siteInfo = SiteInfo::first();
        View::share('website_info', $siteInfo);
    }
}
