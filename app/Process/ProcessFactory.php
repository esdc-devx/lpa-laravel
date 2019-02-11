<?php

namespace App\Process;

use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstanceForm;
use Exception;
use Process;

class ProcessFactory
{
    use UsesProcessInstance;

    /**
     * Start a process instance for an entity.
     * If none is provided, it will create one from its factory 
     * when only one entity type is mapped to the process definition.
     *
     * @param  mixed $processDefinition
     * @param  App\Models\BaseModel $entity
     * @return $this
     */
    public function startProcess($processDefinition, $entity = null)
    {
        // Load process definition from its key.
        if (is_string($processDefinition)) {
            $processDefinition = ProcessDefinition::getByKey($processDefinition)->firstOrFail();
        }

        // When no entity is passed as argument, create one from factory.
        if (is_null($entity)) {
            $supported = $processDefinition->entityTypes->pluck('entity_type');
            if ($supported->count() > 1) {
                throw new Exception("Process definition [ $processDefinition->name_key ] supports more than one entity type, none was provided.");
            }
            // If only one entity type is supported, use it be default.
            $entityType = $supported->first();
            $entity = factory(entity_class($entityType))->create();
        }

        // Start process and retrieve process instance.
        $this->processInstance = Process::startProcess($processDefinition, $entity)->getProcessInstance();

        return $this;
    }

    /**
     * Fill and submit process instance forms up to a certain point specified as an argument.
     * If no form key is provided, it will complete the whole process.
     *
     * @param  string $formKey
     * @return $this
     */
    public function complete($formKey = null)
    {
        if (is_null($this->processInstance)) {
            throw new Exception('Cannot complete process, you must first load a process instance.');
        }

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
    }

    /**
     * Fill form with fake data and submit it.
     *
     * @param  App\Models\Process\ProcessInstanceForm $processInstanceForm
     * @return void
     */
    protected function completeForm($processInstanceForm)
    {
        // Get some fake data from factory.
        $factoryData = $this->getFactoryData($processInstanceForm->definition->name_key);

        // Load form data and format lists to only use ids.
        $formData = $processInstanceForm->formData->formatListsOutput()->toArray();

        // Update process instance form with fake data and submit the form.
        $processInstanceForm->saveForm(array_replace_recursive($formData, $factoryData))->submit();
    }

    /**
     * Get data from model factory.
     *
     * @param  string $entityType
     * @return array
     */
    protected function getFactoryData($entityType)
    {
        return factory(entity_class($entityType))->make()->toArray();
    }

}
