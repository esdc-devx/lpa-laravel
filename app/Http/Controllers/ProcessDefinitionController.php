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
        return $this->respond([
            'process_definitions' => ProcessDefinition::where('entity_type', $entityClass::getEntityType())->get()
        ]);
    }

    /**
     * Start a new process for a given entity.
     *
     * @param  ProcessDefinition $processDefinition
     * @return \Illuminate\Http\Response
     */
    public function startProcess($processDefinition)
    {
        $entity = entity($processDefinition->entity_type)->findOrFail(request()->get('entity_id'));
        $this->authorize('start-process', [$entity, $processDefinition]);
        return $this->respond([
            'process_instance' => Process::startProcess($processDefinition, $entity)->getProcessInstance()
        ]);
    }
}
