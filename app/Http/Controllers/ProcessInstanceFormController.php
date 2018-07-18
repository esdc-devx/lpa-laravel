<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessInstanceFormRequest;
use App\Models\Process\ProcessInstanceForm;

class ProcessInstanceFormController extends APIController
{
    /**
     * Retrieve a process instance form.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function show(ProcessInstanceForm $processInstanceForm)
    {
        return $this->respond($processInstanceForm);
    }

    /**
     * Set current user as the process instance form editor.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function claim(ProcessInstanceForm $processInstanceForm)
    {
        $this->authorize('claim', $processInstanceForm);

        return $this->respond(
            $processInstanceForm->claim()
        );
    }

    /**
     * Remove current process instance form editor.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function unclaim(ProcessInstanceForm $processInstanceForm)
    {
        $this->authorize('unclaim', $processInstanceForm);

        return $this->respond(
            $processInstanceForm->unclaim()
        );
    }

    /**
     * Edit process instance form data.
     *
     * @param  ProcessInstanceFormRequest $request
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function edit(ProcessInstanceFormRequest $request, ProcessInstanceForm $processInstanceForm)
    {
        // Save form data.
        return $this->respond(
            $processInstanceForm->saveForm($request->all())
        );
    }

    /**
     * Submit process instance form.
     *
     * @param  ProcessInstanceFormRequest $request
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function submit(ProcessInstanceFormRequest $request, ProcessInstanceForm $processInstanceForm)
    {
        // Save form data and then submit form.
        return $this->respond(
            $processInstanceForm->saveForm($request->all())->submit()
        );
    }
}
