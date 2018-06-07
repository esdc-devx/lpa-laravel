<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Laravel\Passport\Passport::ignoreMigrations();

        // Bind model classes to their entity_type string.
        foreach(config('app.entity_types') as $alias => $modelClass) {
            $this->app->bind($alias, $modelClass);
        }
    }
}
