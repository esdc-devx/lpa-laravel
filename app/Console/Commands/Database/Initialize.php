<?php

namespace App\Console\Commands\Database;

use App\Console\BaseCommand;
use Schema;

class Initialize extends BaseCommand
{
    protected $signature = 'db:init {--force} {--populate}';
    protected $description = 'Creates all tables and run database seeds.';

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
        if ($this->option('force') || $this->confirm('You are about to drop all database tables, do you wish to continue?')) {
            // Drop all database tables.
            $this->line('Dropping all database tables...');
            Schema::dropAllTables();
            $this->info('Tables dropped successfully.');

            // Call migrate command to recreate all database tables.
            $this->newline('Migrating database...');
            $this->call('migrate');

            // Call db:seed command to populate data.
            $this->newline('Seeding database...');
            // Force composer to discover all missing seeder classes.
            exec('composer dump-autoload');
            $this->call('db:seed');
            $this->info('Database seeded successfully.');

            // If populate option is on, run command to insert some fake data.
            if ($this->option('populate')) {
                $this->call('db:populate');
            }

            // Done.
            $this->success('Database was reverted successfully.');
        }
    }
}
