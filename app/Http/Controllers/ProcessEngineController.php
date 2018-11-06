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
        $response = 'Message [:email_key] for process instance id [:engine_process_instance_id] has been successfully sent to [:addressee_list] on [:date].';
        $replace = [
            ':email_key'                  => $emailKey,
            ':engine_process_instance_id' => $engineProcessInstanceId,
            ':addressee_list'             => join($request->addresseeList, ', '),
            ':date'                       => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        return $this->respond(
            str_replace(array_keys($replace), array_values($replace), $response)
        );
    }
}
