<?php

namespace App\Console\Commands\Database;

//use Illuminate\Console\Command;
use App\Console\BaseCommand;
use Illuminate\Support\Facades\Schema;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Revert extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:revert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revert database to its initial state.';

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
        // Drop all database tables.
        if (!$this->confirm('You are about to drop all database tables, do you wish to continue?')) {
            return false;
        }
        $this->line('Dropping all database tables...');
        Schema::dropAllTables();
        $this->info('Tables dropped successfully.');

        // Call migrate command to recreate all database tables.
        $this->newline('Migrating database...');
        $this->call('migrate');

        // Call db:seed command to populate data.
        $this->newline('Seeding database...');
        $this->call('db:seed');
        $this->info('Database seeded successfully.');

        // Done.
        $this->success('Database was reverted successfully.');
    }
}
