<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormInstanceFormRequest;
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
     * @param  App\Models\BaseModel $processInstanceFormData
     * @return \Illuminate\Http\Response
     */
    public function edit($processInstanceFormData)
    {
        $this->authorize('edit', $processInstanceFormData->processInstanceForm);

        // @todo: Update form data.
        // @todo: Update form modified by/on.
        // @todo: Update process modified by/on.
        // @todo: Update entity modified by/on.


        return $this->respond([
        ]);
    }
}
