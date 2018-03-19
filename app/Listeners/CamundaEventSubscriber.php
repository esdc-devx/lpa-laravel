<?php

namespace App\Listeners;

use App\Camunda\Camunda;
use App\Models\User\User;
use App\Repositories\UserRepository;

class CamundaEventSubscriber
{
    protected $camunda;
    protected $users;

    public function __construct(Camunda $camunda, UserRepository $users)
    {
        $this->camunda = $camunda;
        $this->users = $users;
    }

    /**
     * Handle user create events.
     */
    public function onUserCreate($event)
    {
        // Create user profile in Camunda.
        $this->camunda->users()->create($event->user);

        // Add user to default user group and organizational unit groups.
        $this->camunda->groups()->add($event->user, $this->camunda->config('app.groups.user'));
        $this->synchronizeGroups($event->user);
    }

    /**
     * Handle user save events.
     */
    public function onUserSave($event)
    {
        // Synchronize user groups with Camunda upon save.
        $this->synchronizeGroups($event->user);
    }

    /**
     * Compare user groups with those stored in Camunda
     * and synchronize them.
     *
     * @param  User $user
     * @return void
     */
    protected function synchronizeGroups(User $user)
    {
        // Store its current group key values in an array for compare operations.
        $currentGroups = array_pluck($user->organizationalUnits, 'unique_key');

        // Get groups currently stored in Camunda for the user.
        $camundaGroups = array_diff(
            array_pluck($this->camunda->groups()->for($user), 'id'),
            [$this->camunda->config('app.groups.user')] // Exclude default user group from the list.
        );

        // Add user to these groups in Camunda.
        foreach (array_diff($currentGroups, $camundaGroups) as $groupId) {
            $this->camunda->groups()->add($user, $groupId);
        }

        // Remove user from these groups in Camunda.
        foreach (array_diff($camundaGroups, $currentGroups) as $groupId) {
            $this->camunda->groups()->remove($user, $groupId);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\UserCreated',
            'App\Listeners\CamundaEventSubscriber@onUserCreate'
        );

        $events->listen(
            'App\Events\UserSaved',
            'App\Listeners\CamundaEventSubscriber@onUserSave'
        );
    }

}
