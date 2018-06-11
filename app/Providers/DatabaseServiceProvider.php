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

        // Add user audit columns.
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

        // Add default table structure for listable models.
        Blueprint::macro('listable', function () {
            $this->increments('id');
            $this->unsignedInteger('parent_id')->default(0);
            $this->string('name_key');
            $this->string('name_en');
            $this->string('name_fr');
            $this->boolean('active')->default(1);
        });

        // Add default table structure for pivot tables.
        Blueprint::macro('pivot', function($table1, $table2, $primary = null) {
            $tableColumn1 = str_singular($table1) . '_id';
            $tableColumn2 = str_singular($table2) . '_id';

            $this->unsignedInteger($tableColumn1);
            $this->unsignedInteger($tableColumn2);

            $this->foreign($tableColumn1)
                ->references('id')
                ->on($table1)
                ->onDelete('cascade');

            $this->foreign($tableColumn2)
                ->references('id')
                ->on($table2)
                ->onDelete('cascade');

            $this->primary([$tableColumn1, $tableColumn2], ($primary ?? null));
        });
    }
}
