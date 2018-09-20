<?php

namespace App\Console\Commands\Camunda;

use App\Console\BaseCommand;
use DB;

class Initialize extends BaseCommand
{
    use ExecutesDatabaseScripts;

    protected $signature = 'camunda:init {--force}';
    protected $description = 'Initialize Camunda database.';
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
        if ($this->option('force') || $this->confirm('You are about to initialize Camunda database, do you wish to continue?')) {
            // Ensure no tables exists in database.
            $this->dropDatabaseTables();

            // Create all tables and initialize data.
            $this->newline('Creating database tables...');
            collect($this->camunda->config('app.storage.create'))->each(function ($file) {
                $this->executeScript($file);
            });
            $this->info('All tables created.');

            $this->success('Camunda database was initialized successfully.');
        }
    }

}
