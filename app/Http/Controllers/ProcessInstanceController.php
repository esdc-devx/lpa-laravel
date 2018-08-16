<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessInstance;

class ProcessInstanceController extends APIController
{
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
