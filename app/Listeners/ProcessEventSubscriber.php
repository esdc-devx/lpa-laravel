<?php

namespace App\Listeners;

use App\Models\Process\ProcessInstance;

class ProcessEventSubscriber
{
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
     * Handle ProcessProjectApprovalCompleted events.
     * These events are fired whenever a Project Approval process
     * is considered completed in Camunda.
     */
    public function onProcessProjectApprovalCompleted($event)
    {
        // Attach all form data entered during the process to the project entity.
        // (i.e. BusinessCase, BusinessCaseAssessment, etc.).
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
            'App\Events\ProcessEntityDeleted',
            'App\Listeners\ProcessEventSubscriber@onProcessEntityDeleted'
        );

        $events->listen(
            'App\Events\ProcessProjectApprovalCompleted',
            'App\Listeners\ProcessEventSubscriber@onProcessProjectApprovalCompleted'
        );
    }

}
