<?php

namespace App\Camunda;

use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use App\Camunda\Exceptions\MissingConfigurationException;

class CamundaServiceProvider extends ServiceProvider
{
    /**
     * Register Camunda service.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Camunda', function (Container $app) {
            $config = $app->make('config')->get('camunda');
            // Make sure Camunda config is properly defined.
            if (is_null($config) || empty(implode('', $config))) {
                throw new MissingConfigurationException();
            }
            return new Camunda();
        });
    }
}
