<?php

namespace App\Console\Commands\Process;

use App\Console\BaseCommand;
use App\Models\Process\ProcessDefinition;
use App\Support\Facades\ProcessPublisher;

class Deploy extends BaseCommand
{

    protected $signature = 'process:deploy {definition} {--force}';
    protected $description = 'Deploy a process definition.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('force') || $this->confirm('Do you wish to deploy this process?')) {
            // Resolve process definition.
            $definitionKey = $this->argument('definition');
            $this->info("Deploying [ {$definitionKey} ] process...");
            ProcessPublisher::load($definitionKey)->deploy();
            $this->success('Process deployed successfully.');
        }
    }
}
