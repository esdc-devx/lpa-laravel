<?php

namespace App\Console\Commands\Application;

use App\Console\BaseCommand;

class Install extends BaseCommand
{
    protected $signature = 'app:install {--force} {--populate}';
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
        if ($this->option('force') || $this->confirm('You are about to install the application, do you wish to continue?')) {
            // If bypass option is on, setup argument for all other commands.
            $bypass = $this->option('force') ? ['--force' => true] : [];

            $this->newline('Generating application keys...');
            $this->call('key:generate');
            $this->call('passport:keys', $bypass);

            $this->newline('Initializing Camunda...');
            $this->call('camunda:init', $bypass);

            $this->newline('Initializing database...');
            $this->call('db:init', $bypass);

            $this->newline('Configuring Camunda...');
            $this->call('camunda:configure', $bypass);

            $this->newline('Deploying processes...');
            $this->call('process:deploy', array_merge($bypass, ['definition' => 'project-approval']));
            $this->newline('');
            $this->call('process:deploy', array_merge($bypass, ['definition' => 'course-development']));

            // If populate option is passed, generate fake data.
            if ($this->option('populate')) {
                $this->call('db:populate');
            }

            $this->success('Application was installed successfully.');
        }
    }
}
