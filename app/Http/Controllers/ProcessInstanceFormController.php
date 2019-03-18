<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessInstanceFormRequest;
use App\Http\Resources\ProcessInstanceFormResource;
use App\Models\Process\ProcessInstanceForm;
use App\Models\User\User;

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
        return $this->respond(
            new ProcessInstanceFormResource($processInstanceForm)
        );
    }

    /**
     * Turn on edit mode on a process instance form.
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
     * Turn off edit mode on a process instance form.
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
     * Remove current process instance form editor.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return \Illuminate\Http\Response
     */
    public function release(ProcessInstanceForm $processInstanceForm)
    {
        $editor = User::whereUsername(request()->get('editor'))->firstOrFail();
        $this->authorize('release', [$processInstanceForm, $editor]);

        return $this->respond(
            $processInstanceForm->release()
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
