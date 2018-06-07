<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessInstance;

class ProcessInstanceController extends APIController
{
    public function show($processInstanceId)
    {
        return $this->respond([
            'process_instance' => ProcessInstance::withProcessDetails()->findOrFail($processInstanceId)
        ]);
    }
}
