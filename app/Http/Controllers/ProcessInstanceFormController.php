<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessInstanceFormRequest;
use App\Models\Process\ProcessInstanceForm;

class ProcessInstanceFormController extends APIController
{
    /**
     * Retrieve a process instance form.
     *
     * @param  App\Models\BaseModel $processInstanceFormData
     * @return \Illuminate\Http\Response
     */
    public function show($processInstanceFormData)
    {
        return $this->respond($processInstanceFormData);
    }

    /**
     * Set current user as the process instance form editor.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function claim($id)
    {
        $processInstanceForm = ProcessInstanceForm::findOrFail($id);

        $this->authorize('claim', $processInstanceForm);

        return $this->respond(
            $processInstanceForm->claim()
        );
    }

    /**
     * Remove current process instance form editor.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function unclaim($id)
    {
        $processInstanceForm = ProcessInstanceForm::findOrFail($id);

        $this->authorize('unclaim', $processInstanceForm);

        return $this->respond(
            $processInstanceForm->unclaim()
        );
    }

    /**
     * Edit process instance form data.
     *
     * @param  ProcessInstanceFormRequest $request
     * @param  App\Models\BaseModel $processInstanceFormData
     * @return \Illuminate\Http\Response
     */
    public function edit(ProcessInstanceFormRequest $request, $processInstanceFormData)
    {
        return $this->respond(
            $processInstanceFormData->saveFormData($request->all())
        );
    }

    /**
     * Submit process instance form data.
     *
     * @param  ProcessInstanceFormRequest $request
     * @param  App\Models\BaseModel $processInstanceFormData
     * @return \Illuminate\Http\Response
     */
    public function submit(ProcessInstanceFormRequest $request, $processInstanceFormData)
    {
        return $this->respond(
            $processInstanceFormData->saveFormData($request->all())
        );
    }
}
