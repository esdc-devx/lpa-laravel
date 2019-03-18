<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProcessInstanceResource;
use App\Models\Process\ProcessInstance;

class ProcessInstanceController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processInstances = ProcessInstance::with('definition', 'state', 'createdBy', 'updatedBy');

        // Filter on entity type.
        if ($entityType = request()->get('entity_type')) {
            $processInstances->where('entity_type', $entityType);
            // Filter on specific entity.
            if ($entityId = request()->get('entity_id')) {
                $processInstances->where('entity_id', $entityId);
            }
        }

        return $this->respond([
            'process_instances' => $processInstances->get()
        ]);
    }

    /**
     * Retrieve a process instance with all of its details.
     *
     * @param  int $processInstanceId
     * @return \Illuminate\Http\Response
     */
    public function show($processInstanceId)
    {
        $processInstance = ProcessInstance::withProcessDetails()->findOrFail($processInstanceId);

        return $this->respond(
            new ProcessInstanceResource($processInstance)
        );
    }

    /**
     * Cancel a running process instance.
     *
     * @param  int $processInstanceId
     * @return \Illuminate\Http\Response
     */
    public function cancel($processInstanceId)
    {
        $this->authorize('cancel', ProcessInstance::findOrFail($processInstanceId));

        return $this->respond([
            'process_instance' => \Process::load($processInstanceId)->cancelProcessInstance()->getProcessInstance(),
        ]);
    }
}
