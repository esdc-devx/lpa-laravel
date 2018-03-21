<?php

namespace App\Console\Commands\Camunda;

use \Exception;
use \PDOException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Console\BaseCommand;

class Revert extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'camunda:revert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert database to its initial state.';

    /**
     * Camunda service.
     *
     * @var App\Camunda\Camunda
     */
    protected $camunda;

    /**
     * Execute a series of actions to configure Camunda
     * on a fresh installed server.
     *
     * @return void
     */
    public function __construct()
    {
        $this->camunda = resolve('App\Camunda\Camunda');
        parent::__construct();
    }

    protected function getScript(string $path)
    {
        try {
            return Storage::get("{$this->camunda->config('app.storage')}/mysql/$path");
        }
        // Handle file not found exception.
        catch (FileNotFoundException $e) {
            $path = storage_path("app/{$e->getMessage()}");
            throw new Exception("File {$path} could not be found.");
        }
    }

    protected function executeScript(string $script)
    {
        try {
            DB::connection('camunda')->unprepared(
                $this->getScript($script)
            );
        }
        // Handle any database exceptions.
        catch (PDOException $e) {
            throw new Exception('An error occured while executing mysql script. Check logs for more details.');
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Drop all database tables.
        if (!$this->confirm('You are about to drop all database tables, do you wish to continue?')) {
            return false;
        }
        $this->line('Dropping all database tables...');
        $this->executeScript('drop/mysql_engine_7.8.0.sql');
        $this->executeScript('drop/mysql_identity_7.8.0.sql');
        $this->info('All tables dropped.');

        // Revert database from sql dump file.
        $this->newline('Reverting database...');
        $this->executeScript('revert/camunda_revert.sql');
        $this->info('Database reverted.');

        $this->success('Camunda database was reverted successfully.');
    }
}
