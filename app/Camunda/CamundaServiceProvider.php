<?php

namespace App\Camunda;

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
        $this->app->bind('App\Camunda\Camunda', function () {
            $config = $this->validateConfig(config('camunda'));
            return new Camunda($config);
        });
    }

    /**
     * Validate Camunda configuration.
     *
     * @param  mixed $config
     * @return array
     */
    protected function validateConfig($config)
    {
        // Ensure that config is defined in .env file.
        if (is_null($config)) {
            throw new MissingConfigurationException('Camunda configurations are missing from .env file.');
        }

        // Ensure that required configs are properly defined.
        foreach (['connection', 'authentication', 'app'] as $key) {
            if (!isset($config[$key])) {
                throw new MissingConfigurationException("Missing configuration for [$key].");
            }
        }

        return $config;
    }
}
