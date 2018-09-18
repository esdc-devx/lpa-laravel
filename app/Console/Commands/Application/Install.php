<?php

namespace App\Console\Commands\Application;

use App\Console\BaseCommand;

class Install extends BaseCommand
{
    protected $signature = 'app:install {--yes}';
    protected $description = 'Install and configure application.';

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
        if ($this->option('yes') || $this->confirm('You are about to install the application, do you wish to continue?')) {
            // If bypass option is on, setup argument for all other commands.
            $bypass = $this->option('yes') ? ['--yes' => true] : [];

            $this->newline('Generating application keys...');
            $this->call('key:generate');
            $this->call('passport:keys', ['--force' => true]);

            $this->newline('Initializing Camunda...');
            $this->call('camunda:init', $bypass);

            $this->newline('Initializing database...');
            $this->call('db:init', $bypass);

            $this->newline('Configuring Camunda...');
            $this->call('camunda:configure', $bypass);

            $this->success('Application was installed successfully.');
        }
    }
}
