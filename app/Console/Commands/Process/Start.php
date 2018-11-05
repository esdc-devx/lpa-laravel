<?php

namespace App\Console\Commands\Process;

use App\Console\BaseCommand;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessForm;
use ProcessFactory;

class Start extends BaseCommand
{

    protected $signature = 'process:start {definition} {--entity=} {--form=}';
    protected $description = 'Start a process for a given definition and populate forms with fake data.';
    protected $definition;
    protected $entity;
    protected $formKey;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Resolve process definition.
        $this->definition = $this->resolveProcessDefinition();

        // Resolve entity if provided as an argument.
        $this->entity = $this->resolveEntity();

        // Resolve form key if provided as an argument.
        $this->formKey = $this->resolveFormKey();

        // Start process using Process Factory class.
        $this->info("Start {$this->definition->name} process...");
        ProcessFactory::startProcess($this->definition, $this->entity)->complete($this->formKey);
        $this->success('Process started successfully.');
    }

    /**
     * Resolve process definition from parameter.
     *
     * @return ProcessDefinition
     */
    function resolveProcessDefinition()
    {
        // Ensure process definition exists.
        if (! ($definition = ProcessDefinition::getByKey($this->argument('definition'))->first())) {
            return $this->error('Unsupported process definition: ' . $this->argument('definition'));
        }

        return $definition;
    }

    /**
     * Resolve entity from option parameter, return null othwerise.
     *
     * @return mixed
     */
    protected function resolveEntity()
    {
        if ($entity = $this->option('entity')) {
            // Ensure entity type matches process definition.
            $entity = explode(':', $entity);
            if ($entity[0] !== $this->definition->entity_type) {
                return $this->error('Unsupported entity type: ' . $entity[0]);
            }
            $entity = entity($entity[0])->findOrFail($entity[1]);
        }

        return $entity;
    }

    /**
     * Resolve form key from option parameter, return null othwerise.
     *
     * @return mixed
     */
    protected function resolveFormKey()
    {
        if ($formKey = $this->option('form')) {
            // Ensure form exists and is part of process definition.
            if (! ($processForm = ProcessForm::getByKey($formKey)->first())) {
                return $this->error('Unsupported form: ' . $this->option('form'));
            }
            if (! $processForm->step->definition->is($this->definition)) {
                return $this->error('Unsupported form: ' . $this->option('form'));
            }
        }

        return $formKey;
    }

}
