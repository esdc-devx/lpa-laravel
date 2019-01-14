<?php

namespace App\Providers;

use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessNotification;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

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

        // Resolve model class from entity type string.
        Route::bind('entityType', function($entityTypeKey) {
            return entity($entityTypeKey);
        });

        // Resolve process instance from engine process instance id.
        Route::bind('engineProcessInstanceId', function($engineProcessInstanceId) {
            return ProcessInstance::whereEngineProcessInstanceId($engineProcessInstanceId)->firstOrFail();
        });

        // Resolve process instance form with its form data entity.
        Route::bind('processInstanceFormData', function ($processInstanceFormId) {
            $processInstanceForm = ProcessInstanceForm::findOrFail($processInstanceFormId)->load('formData');
            // Format form data lists as an array of ids to make it easier when working with forms.
            $processInstanceForm->formData->formatListsOutput();
            return $processInstanceForm;
        });

        // Resolve process notification from a process definition key and a notification key.
        Route::bind('notification', function($notification) {
            $processDefinitionKey = request()->route()->parameter('processDefinition');
            return ProcessNotification::resolve($processDefinitionKey, $notification);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapProcessEngineRoutes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
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

    /**
     * Define the process engine routes to allow communication between both systems.
     *
     * @return void
     */
    protected function mapProcessEngineRoutes()
    {
        Route::prefix('process-engine')
            ->middleware('json', 'auth.process-engine', 'bindings')
            ->namespace($this->namespace)
            ->group(base_path('routes/process_engine.php'));
    }
}
