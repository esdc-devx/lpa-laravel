<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
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

        // Map database entity_type column values to model classes.
        // @note: Relation::getMorphedModel('project') -> returns class path.
        Relation::morphMap([
            'project' => 'App\Models\Project\Project',
            'business-case' => 'App\Models\Project\BusinessCase',
        ]);

        // Log database queries with their execution time.
        if (config('app.log_db_queries')) {
            DB::listen(function ($query) {
                logger($query->sql, [
                    'bindings' => $query->bindings,
                    'time' => $query->time
                ]);
            });
        }

    }
}
