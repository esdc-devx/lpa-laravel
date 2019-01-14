<?php

namespace App\Models\InformationSheet\Sheets\ProcessInstance\ProjectApproval;

use App\Events\Process\ProjectApprovalStarted;
use App\Models\InformationSheet\InformationSheet;

class GateOneProjectApproval
{
    /**
     * Respond to ProjectApprovalStarted event to create and associate
     * an information sheet to the process instance.
     *
     * @param  ProjectApprovalStarted $event
     * @return void
     */
    public function onProjectApprovalStarted($event)
    {
        // Create gate one approval information sheet for process instance.
        InformationSheet::createFromDefinition(
            $event->processInstance, 'project-approval.gate-one-project-approval'
        );
    }

    /**
     * Return data for a gate one project approval information sheet.
     *
     * @param  App\Models\Process\ProcessInstance $processInstance
     * @return array
     */
    public function getData($processInstance)
    {
        // Load process instance relationships.
        $processInstance->load(
            'state', 'definition'
        );

        // Load project entity and its relationships.
        $processInstance->load(
            'entity.organizationalUnit.director', 'entity.state', 'entity.createdBy', 'entity.updatedBy'
        );

        // Load all process instance forms data.
        $processInstance->forms->each->load('formData');

        return $processInstance->toArray();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ProjectApprovalStarted::class,
            static::class . '@onProjectApprovalStarted'
        );
    }
}
