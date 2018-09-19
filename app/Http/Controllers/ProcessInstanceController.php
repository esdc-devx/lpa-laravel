<?php

namespace App\Http\Controllers;

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
        $processInstances = ProcessInstance::with('definition', 'state');

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
        return $this->respond([
            'process_instance' => ProcessInstance::withProcessDetails()->findOrFail($processInstanceId)
        ]);
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
