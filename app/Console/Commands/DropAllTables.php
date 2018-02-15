<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DropAllTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all database tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->confirm('Do you wish to continue?')) {
            \Schema::dropAllTables();
            $this->info('All database tables were dropped.');
        }
    }
}
