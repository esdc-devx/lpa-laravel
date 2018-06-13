<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormInstanceFormRequest;

class ProcessInstanceFormController extends APIController
{
    /**
     * Retrieve a process instance form.
     *
     * @param  App\Models\BaseModel $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function show($processInstanceForm)
    {
        return $this->respond($processInstanceForm);
    }
}
