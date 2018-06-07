<?php

namespace App\Providers;

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
