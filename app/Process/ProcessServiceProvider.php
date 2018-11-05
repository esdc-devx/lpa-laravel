<?php

namespace App\Process;

use Illuminate\Support\ServiceProvider;

class ProcessServiceProvider extends ServiceProvider
{
    /**
     * Register process services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('process.manager', function ($app) {
            return new ProcessManager($app->make('App\Camunda\Camunda'));
        });

        $this->app->singleton('process.factory', function ($app) {
            return new ProcessFactory();
        });
    }

}
