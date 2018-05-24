<?php

namespace App\Providers;

use App\Models\Process\ProcessDefinition;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
        //

        parent::boot();

        // Configure route model bindings.
        Route::bind('processDefinition', function ($value) {
            return ProcessDefinition::getByKey($value)->first() ?? abort(Response::HTTP_NOT_FOUND);
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
