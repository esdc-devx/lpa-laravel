<?php

namespace App\Listeners;

class UserEventSubscriber
{
    /**
     * Handle UserSaved events.
     */
    public function onUserSaved($event)
    {
        $user = $event->user;
        $user->processInstanceForms->each(function($processInstanceForm) use ($user) {
            // If user can no longer edit this form, remove its ownership.
            if ($user->cant('edit', $processInstanceForm)) {
                $processInstanceForm->unclaim();
            }
        });
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserSaved',
            'App\Listeners\UserEventSubscriber@onUserSaved'
        );
    }
}
