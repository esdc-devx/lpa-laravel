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
            logger('-- ' . request()->path() . ' --');
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

            $this->referenceOn('created_by', 'users')->onDelete('set null');
            $this->referenceOn('updated_by', 'users')->onDelete('set null');
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

        // Add foreign key macro.
        Blueprint::macro('referenceOn', function ($column, $table, $references = 'id') {
            return $this->foreign($column, db_index_name($this->getTable() . '_' . $column))
                ->references($references)
                ->on($table);
        });

        // Add default table structure for pivot tables.
        Blueprint::macro('pivot', function($table1, $table2) {
            $table = $this->getTable();
            $tableColumn1 = str_singular($table1) . '_id';
            $tableColumn2 = str_singular($table2) . '_id';

            $this->unsignedInteger($tableColumn1);
            $this->unsignedInteger($tableColumn2);

            $this->referenceOn($tableColumn1, $table1)->onDelete('cascade');
            $this->referenceOn($tableColumn2, $table2)->onDelete('cascade');

            $this->primary([$tableColumn1, $tableColumn2], db_index_name("{$table}_primary"));
        });
    }
}
