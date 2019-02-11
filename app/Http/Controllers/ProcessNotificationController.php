<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessNotificationFormRequest;
use App\Models\Process\ProcessNotification;
use Illuminate\Http\Request;

class ProcessNotificationController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $processNotifications = ProcessNotification::with('processDefinition', 'updatedBy')->get();

        return $this->respond([
            'process_notifications' => $processNotifications
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $processNotification = ProcessNotification::findOrFail($id);
        $this->authorize('update', $processNotification);

        return $this->respond([
            'process_notification' => $processNotification,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProcessNotificationFormRequest  $request
     * @param  ProcessNotification  $processNotification
     * @return \Illuminate\Http\Response
     */
    public function update(ProcessNotificationFormRequest $request, ProcessNotification $processNotification)
    {
        // Ensure that user cannot update any other attributes.
        $data = $request->only([
            'name_en',
            'name_fr',
            'subject_en',
            'subject_fr',
            'body_en',
            'body_fr',
        ]);

        // Update data and audit field.
        $processNotification->update(
            array_merge($data, [
                'updated_by' => auth()->user()->id,
            ])
        );

        return $this->respond([
            'process_notification' => $processNotification,
        ]);
    }
}
