<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessNotification;
use Illuminate\Http\Request;
use Process;

class ProcessEngineController extends APIController
{
    /**
     * Route made available to the process engine to send email notifications
     * during process progression.
     *
     * @param  Request $request
     * @param  string  $processDefinitionKey
     * @param  ProcessNotification $notification
     * @param  ProcessInstance $processInstance
     * @return \Illuminate\Http\Response
     */
    public function notifications(Request $request, $processDefinitionKey, ProcessNotification $notification, ProcessInstance $processInstance)
    {
        Process::sendNotification($notification, $processInstance, $request->addresseeList);

        return $this->respondNoContent();
    }
}
