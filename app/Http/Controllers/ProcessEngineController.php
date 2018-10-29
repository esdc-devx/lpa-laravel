<?php

namespace App\Http\Controllers;

use App\Models\Process\ProcessInstance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProcessEngineController extends APIController
{
    public function notifications(Request $request, $emailKey, $engineProcessInstanceId)
    {
        //@todo: Implement email notification logic.
        return $this->respond(
            str_replace(
                [':email_key', ':engine_process_instance_id', ':addressee_list', ':date'],
                [$emailKey, $engineProcessInstanceId, join($request->addresseeList, ', '), Carbon::now()],
                'Message [:email_key] for process instance id [:engine_process_instance_id] has been successfully sent to [:addressee_list] on [:date].'
            )
        );
    }
}
