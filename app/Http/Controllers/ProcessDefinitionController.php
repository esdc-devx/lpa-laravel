<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessDefinition;
use Process;

class ProcessDefinitionController extends APIController
{
    /**
     * Retrieve all processes for a specific entity type.
     *
     * @param  App\Models\BaseModel $entityClass
     * @return \Illuminate\Http\Response
     */
    public function show($entityClass)
    {
        $processDefinitions = ProcessDefinition::getFor($entityClass);

        return $this->respond([
            'process_definitions' => $processDefinitions,
        ]);
    }

    /**
     * Start a new process for a given entity.
     *
     * @param  ProcessDefinition $processDefinition
     * @return \Illuminate\Http\Response
     */
    public function startProcess(ProcessDefinition $processDefinition)
    {
        $entity = entity(request()->get('entity_type'), request()->get('entity_id'));
        $this->authorize('start-process', [$entity, $processDefinition]);
        $processInstance = Process::startProcess($processDefinition, $entity)->getProcessInstance();

        return $this->respond([
            'process_instance' => $processInstance
        ]);
    }
}
