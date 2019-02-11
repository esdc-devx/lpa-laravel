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
     * Handle process deployed event.
     */
    public function onProcessDeployed($event)
    {
        // Whenever a process gets deployed, also deploy its definition in Camunda.
        $processDefinition = $event->processDefinition;
        $deploymentFile = kebab_case($processDefinition->name_en) . '.bpmn';
        $this->camunda->processes()->deploy([
            'name' => $processDefinition->name_en,
            'file' => $deploymentFile,
        ]);
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

    /**
     * Handle process instance form submission event.
     */
    public function onProcessInstanceFormSubmit($event)
    {
        // Complete task associated with process instance form and
        // send some variables back to Camunda.
        $this->camunda->tasks()->complete(
            $event->processInstanceForm->engine_task_id,
            $this->getFormDataVariables($event->processInstanceForm->formData)
        );

        // Update process instance variables and tasks.
        \Process::load($event->processInstanceForm->step->processInstance)->updateProcessInstance();
    }

    /**
     * Handle ProcessEntityUpdated events.
     * These events are fired whenever an entity that could have
     * process instances is being updated. (i.e. Project, LearningProduct, etc.).
     */
    public function onProcessEntityUpdated($event)
    {
        // If the entity has a running process and that its organizational unit was changed,
        // reflect that change in Camunda.
        $entity = $event->entity;
        if ($entity->isDirty('organizational_unit_id') && $entity->currentProcess) {
            // Update owner process variable in Camunda.
            $this->camunda->processes()->updateVariables($entity->currentProcess->engine_process_instance_id, [
                'owner' => ['value' => $entity->organizationalUnit->name_key]
            ]);

            // Update candidate group for all active tasks that were previously assigned to the organizational unit.
            $entity->currentProcess->forms->each(function($processInstanceForm) use ($entity) {
                if ($processInstanceForm->organizationalUnit && $processInstanceForm->organizationalUnit->owner && $processInstanceForm->engine_task_id) {
                    // Remove all candidate groups for the task.
                    collect($this->camunda->tasks()->getCandidates($processInstanceForm->engine_task_id))
                        ->whereNotIn('groupId', [null])
                        ->each(function($group) use ($processInstanceForm) {
                            $this->camunda->tasks()->removeCandidate($processInstanceForm->engine_task_id, [
                                'groupId' => $group->groupId,
                            ]);
                        });

                    // Add a candidate group for the new organizational unit.
                    $this->camunda->tasks()->addCandidate($processInstanceForm->engine_task_id, [
                        'groupId' => $entity->organizationalUnit->name_key,
                    ]);
                }
            });
        }
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
     * Retrieve form assessments decisions and format them into an array to be
     * sent back to Camunda when completing a task.
     *
     * @param  App\Models\Process\ProcessInstanceFormDataModel $processInstanceFormData
     * @return array
     */
    protected function getFormDataVariables($processInstanceFormData)
    {
        $variables = [];

        // If form data contains any assessments, return decisions back to Camunda.
        if ($assessments = $processInstanceFormData->assessments ?? null) {
            foreach ($assessments as $assessment) {
                $variables[camel_case("decision-form-{$assessment->assessed_process_form}")] = [
                    'type'  => 'String',
                    'value' => $assessment->decision->name_key ?? null,
                ];
            }
        }

        // If form contains a cancellation decision on the process, return it back to Camunda.
        if (isset($processInstanceFormData->is_entity_cancelled)) {
            $variables['isEntityCancelled'] = [
                'type'  => 'Boolean',
                'value' => $processInstanceFormData->is_entity_cancelled,
            ];
        }

        // @note: Could maybe add a method on the ProcessInstanceFormDataModel to send back other form variables when needed.

        return ! empty($variables) ? $variables : null;
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
            'App\Events\ProcessDeployed',
            'App\Listeners\CamundaEventSubscriber@onProcessDeployed'
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

        $events->listen(
            'App\Events\ProcessEntityUpdated',
            'App\Listeners\CamundaEventSubscriber@onProcessEntityUpdated'
        );
    }

}
