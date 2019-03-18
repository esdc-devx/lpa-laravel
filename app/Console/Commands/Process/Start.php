<?php

namespace App\Console\Commands\Process;

use App\Console\BaseCommand;
use App\Models\Process\ProcessDefinition;

class Start extends BaseCommand
{
    protected $signature = 'process:start {definition} {--entity=} {--form=} {--state=*}';
    protected $description = 'Start a process for a given definition and populate forms with fake data.';

    // Process definition key (i.e. project-approval, course-development, etc.).
    protected $definition;
    // (Optional) Entity to start process for using format [$entityType]:[$entityId] (i.e. project:32, course:3, etc.).
    protected $entity;
    // (Optional) Complete process up to this form key (i.e. business-case, design-assessment, etc.).
    protected $formKey;
    // (Optional) Pass the state argument to the process factory when creating a new entity.
    protected $states;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Resolve process definition argument.
        $this->definition = $this->resolveProcessDefinition();

        // Resolve entity if provided as an option.
        $this->entity = $this->resolveEntity();

        // Resolve form key if provided as an option.
        $this->formKey = $this->resolveFormKey();

        // Resolve factory states if provided as option.
        $this->states = $this->resolveStates();

        // Start process using Process Factory class.
        resolve('process.factory')
            ->startProcess($this->definition, $this->entity, $this->states)
            ->complete($this->formKey);

        $this->success('Process completed successfully.');
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
     * @return mixed | App\Models\BaseModel or null
     */
    protected function resolveEntity()
    {
        if ($entity = $this->option('entity')) {
            $entity = explode(':', $entity);
            $entity = entity($entity[0], $entity[1]);
        }

        return $entity;
    }

    /**
     * Resolve form key from option parameter, return null othwerise.
     *
     * @return mixed | string or null
     */
    protected function resolveFormKey()
    {
        return $this->option('form') ?? null;
    }

    /**
     * Resolve factory states from option parameter, return null otherwise.
     *
     * @return mixed | string or null
     */
    protected function resolveStates()
    {
        return $this->option('state') ?? [];
    }
}
