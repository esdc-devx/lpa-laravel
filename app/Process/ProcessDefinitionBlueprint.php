<?php

namespace App\Process;

abstract class ProcessDefinitionBlueprint
{
    // Process definition blueprint classes should implement these methods.
    abstract protected function getDefinition();
    abstract protected function getStructure();
    abstract protected function getNotifications();
    abstract protected function getInformationSheets();

    /**
     * Allow a process to be started by default.
     * Override this method to add extra logic to authorize process initiation.
     *
     * @return boolean
     */
    public function authorize($entity) {
        return true;
    }

    /**
     * Override this method to send extra variables to Camunda
     * when starting a process.
     *
     * @param  App\Models\BaseModel $entity
     * @return array
     */
    public function getProcessVariablesFor($entity) {
        return [];
    }

    /**
     * Retrieve all blueprint data (process definition, structure, notifications, and information sheets).
     *
     * @return array
     */
    public function getData()
    {
        return [
            // Process definition.
            'definition' => $this->getDefinition(),

            // Process steps and forms.
            'structure' => $this->getStructure(),

            // Process notifications.
            'notifications' => $this->getNotifications(),

            // Process information sheets.
            'information_sheets' => $this->getInformationSheets(),
        ];
    }
}
