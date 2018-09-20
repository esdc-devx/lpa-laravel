<?php

namespace App\Console\Commands\Database;

use App\Console\BaseCommand;
use PopulateSeeder;

class Populate extends BaseCommand
{
    protected $signature = 'db:populate';
    protected $description = 'Populate fake data for testing purposes.';

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
        $this->newline('Populating fake data...');

        // Run PopulateSeeder class to create fake data.
        resolve(PopulateSeeder::class)->run();
    }
}
