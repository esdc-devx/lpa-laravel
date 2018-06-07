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
}
