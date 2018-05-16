<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
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

        // Add a macro to the blueprint for user audit columns.
        Blueprint::macro('auditable', function () {
            $this->unsignedInteger('created_by')->nullable();
            $this->unsignedInteger('updated_by')->nullable();

            $this->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $this->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
}
