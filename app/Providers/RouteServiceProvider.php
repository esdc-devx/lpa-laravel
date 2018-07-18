<?php

namespace App\Providers;

use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstanceForm;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /*
         * Configure route model bindings.
         */

        // Resolve process definition from its key.
        Route::bind('processDefinition', function ($processDefinitionKey) {
            return ProcessDefinition::getByKey($processDefinitionKey)->firstOrFail();
        });

        // Resolve model class from entity type string.
        Route::bind('entityType', function($entityTypeKey) {
            return entity($entityTypeKey);
        });

        // Resolve process instance form with its form data entity.
        Route::bind('processInstanceFormData', function ($processInstanceFormId) {
            return ProcessInstanceForm::with([
                'definition', 'organizationalUnit', 'currentEditor', 'formData',
            ])->findOrFail($processInstanceFormId);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $locale = Request::segment(1);
        $this->app->setLocale($locale);

        Route::prefix($locale)
             ->middleware('web', 'language')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $locale = Request::segment(1);
        $this->app->setLocale($locale);

        Route::prefix("$locale/api")
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
