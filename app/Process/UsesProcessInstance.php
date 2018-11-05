<?php

namespace App\Process;

use App\Models\Process\ProcessInstance;

trait UsesProcessInstance
{
    protected $processInstance;

    /**
     * Return current process instance being loaded.
     *
     * @return ProcessInstance
     */
    public function getProcessInstance()
    {
        return $this->processInstance;
    }

    /**
     * Load a process instance to work with.
     *
     * @param  mixed $processInstance
     * @return $this
     */
    public function load($processInstance)
    {
        // Load process instance from id.
        if (is_numeric($processInstance)) {
            $processInstance = ProcessInstance::findOrFail($processInstance);
        }
        // Load process instance from engine process instance id.
        elseif (is_string($processInstance)) {
            $processInstance = ProcessInstance::where('engine_process_instance_id', $processInstance)->firstOrFail();
        }
        // Ensure argument passed is an instance of process instance model.
        elseif (! $processInstance instanceof ProcessInstance) {
            throw new \Exception('Could not load process instance. Invalid argument provided.');
        }

        $this->processInstance = $processInstance;

        return $this;
    }

}
