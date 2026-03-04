<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS on production so all asset() and url() calls
        // generate https:// URLs, preventing mixed-content browser errors.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
