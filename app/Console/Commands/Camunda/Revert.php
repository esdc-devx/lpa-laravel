<?php

namespace App\Console\Commands\Camunda;

use App\Console\BaseCommand;
use Storage;

class Revert extends BaseCommand
{
    use ExecutesDatabaseScripts;

    //@note: Possibly add an optional parameter for revert filename.
    protected $signature = 'camunda:revert {--force}';
    protected $description = 'Revert Camunda database to a given snapshot.';
    protected $camunda;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->camunda = resolve('App\Camunda\Camunda');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Drop all database tables.
        if ($this->option('force') || $this->confirm('You are about to drop all database tables, do you wish to continue?')) {

            // Ensure a database revert file exists before doing anything.
            if (! Storage::exists($this->camunda->config('app.storage.revert'))) {
                $revertFile = str_replace('\\', '/', storage_path('app/' . $this->camunda->config('app.storage.revert')));
                return $this->error("Could not locate Camunda database revert file [$revertFile].");
            }

            // Drop all existing tables.
            $this->dropDatabaseTables();

            // Revert database from sql dump file.
            $this->newline('Reverting database...');
            $this->executeScript($this->camunda->config('app.storage.revert'));
            $this->info('Database reverted.');

            $this->success('Camunda database was reverted successfully.');
        }
    }
}
