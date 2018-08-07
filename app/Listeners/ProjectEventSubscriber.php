<?php

namespace App\Listeners;

class ProjectEventSubscriber
{
    /**
     * Handle Project Approval process completed events.
     */
    public function onProcessProjectApprovalCompleted($event)
    {
        // Attach all form data entered during the process to the project entity.
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
            'App\Events\ProcessProjectApprovalCompleted',
            'App\Listeners\ProjectEventSubscriber@onProcessProjectApprovalCompleted'
        );
    }

}
