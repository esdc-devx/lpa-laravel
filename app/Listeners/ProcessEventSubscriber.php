<?php

namespace App\Listeners;

use App\Models\Process\ProcessInstance;

class ProcessEventSubscriber
{
    /**
     * Handle ProcessEntityUpdated events.
     * These events are fired whenever an entity that could have
     * process instances is being updated. (i.e. Project, LearningProduct, etc.).
     */
    public function onProcessEntityUpdated($event)
    {
        // Check if entity has a running process and its organizational unit was changed.
        $entity = $event->entity;
        if ($entity->isDirty('organizational_unit_id') && $entity->currentProcess) {
            $entity->currentProcess->forms->each(function($processInstanceForm) use ($entity) {
                if ($processInstanceForm->organizationalUnit && $processInstanceForm->organizationalUnit->owner) {
                    // Re-assign process instance form to its new organizational unit.
                    $processInstanceForm->organizationalUnit()->associate($entity->organizationalUnit);
                    $processInstanceForm->save();

                    // Remove any editor that can no longer edit the form.
                    if ($processInstanceForm->currentEditor && $processInstanceForm->currentEditor->cant('edit', $processInstanceForm)) {
                        $processInstanceForm->unclaim();
                    }
                }
            });
        }
    }

    /**
     * Handle ProcessEntityDeleted events.
     * These events are fired whenever an entity that could have
     * process instances is being deleted. (i.e. Project, LearningProduct, etc.).
     */
    public function onProcessEntityDeleted($event)
    {
        // Delete all process instances associated with entity being deleted.
        ProcessInstance::destroy(
            $event->entity->processInstances->pluck('id')->all()
        );
    }

    /**
     * Handle ProjectApprovalCompleted events.
     * These events are fired whenever a Project Approval process
     * is considered completed in Camunda.
     */
    public function onProcessProjectApprovalCompleted($event)
    {
        // Attach all form data entered during the process to the project entity.
        // (i.e. BusinessCase, PlannedProductList, etc.).
        \Process::load($event->processInstance)->attachFormData();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ProcessEntityUpdated',
            'App\Listeners\ProcessEventSubscriber@onProcessEntityUpdated'
        );

        $events->listen(
            'App\Events\ProcessEntityDeleted',
            'App\Listeners\ProcessEventSubscriber@onProcessEntityDeleted'
        );

        $events->listen(
            'App\Events\Process\ProjectApprovalCompleted',
            'App\Listeners\ProcessEventSubscriber@onProcessProjectApprovalCompleted'
        );
    }

}
