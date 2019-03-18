<?php

namespace App\Process;

use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessForm;
use App\Models\Process\ProcessInstanceForm;
use App\Support\ConsoleOutput;
use Exception;

class ProcessFactory
{
    use UsesProcessInstance;

    protected $processDefinition;
    protected $entity;
    protected $output;

    public function __construct(ConsoleOutput $output) {
        $this->output = $output;
    }

    /**
     * Start a process instance for an entity.
     * If none is provided, it will create one from its factory
     * when only one entity type is mapped to the process definition.
     *
     * @param  mixed $processDefinition | string or ProcessDefinition
     * @param  App\Models\BaseModel $entity
     * @param  array $states
     * @return $this
     */
    public function startProcess($processDefinition, $entity = null, $states = [])
    {
        // Resolve process definition.
        $this->processDefinition = $this->resolveProcessDefinition($processDefinition);

        // Resolve entity from argument.
        $this->entity = $this->resolveProcessEntity($entity, $states);

        // Start process and retrieve process instance.
        $this->processInstance = resolve('process.manager')
            ->startProcess($this->processDefinition, $this->entity)
            ->getProcessInstance();

        return $this;
    }

    /**
     * Resolve process definition argument.
     * If a string was passed, load definition from its key.
     *
     * @param  mixed $processDefinition | string or ProcessDefinition
     * @return void
     */
    protected function resolveProcessDefinition($processDefinition)
    {
        // If argument is a string, ;oad process definition from its key.
        if (is_string($processDefinition)) {
            return ProcessDefinition::getByKey($processDefinition)->firstOrFail();
        }
        // Argument passed is already a process definition instance.
        if ($processDefinition instanceof ProcessDefinition) {
            return $processDefinition;
        }
        throw new Exception('Invalid process definition argument.');
    }

    /**
     * Resolve process entity. If none was provided, create one from factory.
     *
     * @param  mixed $entity
     * @param  mixed $states
     * @return App\Models\BaseModel
     */
    protected function resolveProcessEntity($entity, $states)
    {
        if (isset($entity)) {
            // Ensure that entity is not currently in a process.
            if ($entity->currentProcess) {
                throw new Exception("Entity is already part of a process.");
            }
            // Ensure that process definition support supplied entity.
            $supported = $this->processDefinition->entityTypes->pluck('entity_type');
            if (! $supported->contains($entity::getEntityType())) {
                throw new Exception('Process definition does not support supplied entity.');
            }
        }

        if (is_null($entity)) {
            // Look for all entity types supported by process definition.
            $supported = $this->processDefinition->entityTypes->pluck('entity_type');
            if ($supported->count() > 1) {
                throw new Exception("Process definition [$this->processDefinition->name_key] supports more than one entity type, none was provided.");
            }
            // If only one entity type is supported, use it be default.
            $entityType = $supported->first();
            $entityClass = entity_class($entityType);
            $factory = factory($entityClass);
            // If any states was provided, define them before creating the entity.
            if (! empty($states)) {
                $factory->states($states);
            }
            $entity = $factory->create();
        }

        return $entity;
    }

    /**
     * Resolve process form from its key.
     * Also ensure that form exists and is part of the process definition.
     *
     * @param  string $formKey
     * @return mixed | null or string
     */
    protected function resolveProcessForm($formKey)
    {
        if (! is_null($formKey)) {
            // Ensure that form exists.
            if (! ($processForm = ProcessForm::getByKey($formKey)->first())) {
                throw new Exception("Form provided [$formKey] does not exists.");
            }
            // Ensure that form is part of current process definition.
            if (! $processForm->step->definition->is($this->processDefinition)) {
                throw new Exception("Form provided [$formKey] is not part of process definition.");
            }
        }
        return $formKey;
    }

    /**
     * Fill and submit process instance forms up to a certain point specified as an argument.
     * If no form key is provided, complete the whole process.
     *
     * @param  string $formKey
     * @return $this
     */
    public function complete($formKey = null)
    {
        if (is_null($this->processInstance)) {
            throw new Exception('Cannot complete process, you must first load a process instance.');
        }

        $processDefinition = $this->processDefinition->name_key;
        $entityType = $this->processInstance->entity_type;
        $entityId = $this->processInstance->entity_id;
        $formKey = $this->resolveProcessForm($formKey);

        // Output console feedback while completing processes.
        $this->output->info("Completing process [$processDefinition] for [$entityType:$entityId] ...");

        // Store all process instance forms in one single collection.
        $processInstanceForms = $this->processInstance->steps->flatMap(function ($step) {
            return $step->forms;
        });

        // If form key is not defined, set to last form key to complete the whole process.
        $formKey = $formKey ?? $processInstanceForms->last()->definition->name_key;

        // Loop through each form and submit them until it reaches the form key defined.
        $processInstanceForms->each(function ($processInstanceForm) use ($formKey) {
            // Get a fresh copy of the model since it was updated during the process.
            $processInstanceForm = $processInstanceForm->fresh();
            if ($processInstanceForm->state->name_key == 'unlocked') {
                $this->completeForm($processInstanceForm);
            }
            return ! ($processInstanceForm->definition->name_key == $formKey);
        });

        return $this;
    }

    /**
     * Fill form with fake data and submit it.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return void
     */
    protected function completeForm($processInstanceForm)
    {
        // Load form data and format lists to only use ids.
        $formData = $processInstanceForm->formData->formatListsOutput()->toArray();

        // Get some fake data from factory.
        $factoryData = $this->getFactoryData($processInstanceForm);

        // Update process instance form with fake data and submit the form.
        $processInstanceForm->saveForm(
            array_replace_recursive($formData, $factoryData)
        )->submit();
    }

    /**
     * Get data from model factory.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return array
     */
    protected function getFactoryData($processInstanceForm)
    {
        $formFactoryClass = entity_class($processInstanceForm->definition->name_key);

        // Pass in the process instance id so that factories can leverage it for special business logic.
        return factory($formFactoryClass)->make([
            'process_instance_id' => $processInstanceForm->formData->process_instance_id,
        ])->toArray();
    }

    /**
     * Get entity attached to the process launched.
     *
     * @return App\Models\BaseModel
     */
    public function getProcessEntity()
    {
        if (! $this->entity) {
            throw new Exception('Cannot retrieve process entity, you must complete a process.');
        }
        // Return a fresh copy of the model.
        return $this->entity->fresh();
    }

}
