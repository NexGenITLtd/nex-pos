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

	    // Share the SiteInfo data with views only if the table exists
	    if (\Schema::hasTable('site_infos')) {
	        $siteInfo = SiteInfo::first();
	        View::share('website_info', $siteInfo);
	    }
	}


}
