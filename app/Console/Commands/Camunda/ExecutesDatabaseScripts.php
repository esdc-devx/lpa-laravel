<?php

namespace App\Console\Commands\Camunda;

use DB;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Schema;
use Storage;

trait ExecutesDatabaseScripts
{
    /**
     * Execute callback against Camunda database and
     * restore database connection afterwards.
     *
     * @param  callable $callback
     * @return void
     */
    protected function useCamundaDatabase($callback)
    {
        $previousDB = config('database.default');
        DB::setDefaultConnection('camunda');
        $callback();
        DB::setDefaultConnection($previousDB);
    }

    /**
     * Load script file from camunda storage.
     *
     * @param  string $path
     * @return string
     */
    protected function getScript($path)
    {
        try {
            return Storage::get($path);
        }
        // Handle file not found exception.
        catch (FileNotFoundException $e) {
            $path = storage_path($e->getMessage());
            throw new Exception("File {$path} could not be found.");
        }
    }

    /**
     * Execute script against Camunda database.
     *
     * @param  string $script
     * @return void
     */
    protected function executeScript(string $script)
    {
        $this->useCamundaDatabase(function() use ($script) {
            try {
                DB::unprepared(
                    $this->getScript($script)
                );
            }
            // Handle any exception during script execution.
            catch (Exception $e) {
                throw new Exception("An error occured while executing script [$script].");
            }
        });
    }

    /**
     * Drop all database tables.
     *
     * @return void
     */
    protected function dropDatabaseTables()
    {
        $this->useCamundaDatabase(function() {
            if ($tables = DB::select('SHOW TABLES')) {
                $this->line('Dropping existing database tables...');

                DB::statement('SET FOREIGN_KEY_CHECKS = 0');
                foreach ($tables as $table) {
                    $array = get_object_vars($table);
                    Schema::drop($array[key($array)]);
                }
                DB::statement('SET FOREIGN_KEY_CHECKS = 1');

                $this->info('All tables dropped.');
            }
        });
    }
}
