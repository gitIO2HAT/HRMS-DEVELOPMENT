<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

         // Get the timezone from the user or any other source
         $userTimezone = 'Asia/Manila';

         // Set the application timezone dynamically
         Config::set('app.timezone', $userTimezone);
    }
}
