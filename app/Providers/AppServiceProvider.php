<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Ignore migrations used for oauth api access manager.
        Passport::ignoreMigrations();

        // Introduced as of Passport 6.0.7, more info:
        // https://github.com/laravel/passport/issues/795
        Passport::withoutCookieSerialization();
    }
}
