<?php

namespace App\Listeners;

use App\Camunda\Camunda;
use App\Camunda\Exceptions\ResourceNotFoundException;
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
        // Ensure we don't create admin account in Camunda since it already exists.
        if ($event->user->username === config('auth.admin.username')) {
            return;
        }

        // Create user profile in Camunda.
        try {
            $this->camunda->users()->get($event->user);
            return;
        }
        // Only create user if it doesn't exist in Camunda.
        catch (ResourceNotFoundException $exception) {
            $this->camunda->users()->create($event->user);
        }

        try {
            // Ensure that default user group exists.
            $this->camunda->groups()->get($this->camunda->config('app.groups.user'));

            // Add user to default user group and organizational unit groups.
            $this->camunda->groups()->add($event->user, $this->camunda->config('app.groups.user'));
            $this->synchronizeGroups($event->user);
        }
        // When creating users during database seed process, groups won't exist yet in Camunda,
        // so we need to bypass the group synchronization until they get added during Camunda configuration.
        catch (ResourceNotFoundException $exception) {
            return;
        }
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
     * Handle process instance form claim event.
     */
    public function onProcessInstanceFormClaim($event)
    {
        // Claim task associated with process instance form.
        if ($event->processInstanceForm->engine_task_id) {
            $this->camunda->tasks()->claim($event->processInstanceForm->engine_task_id, $event->user);
        }
    }

    /**
     * Handle process instance form unclaim event.
     */
    public function onProcessInstanceFormUnclaim($event)
    {
        // Unclaim task associated with process instance form.
        if ($event->processInstanceForm->engine_task_id) {
            $this->camunda->tasks()->unclaim($event->processInstanceForm->engine_task_id);
        }
    }

    public function onProcessInstanceFormSubmit($event)
    {
        // Complete task associated with process instance form.
        //@todo: Add some logic to pass in some variables back to Camunda when submitting a form assessment.
        $this->camunda->tasks()->complete($event->processInstanceForm->engine_task_id);

        // Update process instance variables and tasks.
        \Process::load($event->processInstanceForm->step->processInstance)->updateProcessInstance();
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
        $currentGroups = array_pluck($user->organizationalUnits, 'name_key');

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

        $events->listen(
            'App\Events\ProcessInstanceFormClaimed',
            'App\Listeners\CamundaEventSubscriber@onProcessInstanceFormClaim'
        );

        $events->listen(
            'App\Events\ProcessInstanceFormUnclaimed',
            'App\Listeners\CamundaEventSubscriber@onProcessInstanceFormUnclaim'
        );

        $events->listen(
            'App\Events\ProcessInstanceFormSubmitted',
            'App\Listeners\CamundaEventSubscriber@onProcessInstanceFormSubmit'
        );
    }

}
