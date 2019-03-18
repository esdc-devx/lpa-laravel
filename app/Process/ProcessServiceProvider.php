<?php

namespace App\Process;

use Illuminate\Support\ServiceProvider;
use App\Support\ConsoleOutput;

class ProcessServiceProvider extends ServiceProvider
{
    /**
     * Register process services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('process.publisher', function ($app) {
            $output = new ConsoleOutput();
            return new ProcessPublisher($output);
        });

        $this->app->singleton('process.manager', function ($app) {
            return new ProcessManager($app->make('App\Camunda\Camunda'));
        });

        $this->app->bind('process.factory', function ($app) {
            $output = new ConsoleOutput();
            return new ProcessFactory($output);
        });
    }

}
