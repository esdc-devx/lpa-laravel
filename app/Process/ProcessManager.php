<?php

namespace App\Process;

use App\Camunda\Camunda;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceStep;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProcessManager {

    protected $camunda;
    protected $processInstance;
    protected $processStates = [];
    protected $processTasks = [];
    protected $processInstanceFormsData;

    public function __construct(Camunda $camunda)
    {
        $this->camunda = $camunda;
    }

    /**
     * Start a process instance.
     *
     * @param  mixed $processDefinition
     * @param  App\Models\BaseModel $entity
     * @return App\Models\Process\ProcessInstance
     */
    public function startProcessInstance($processDefinition, $entity)
    {
        // Make sure no process are currently running.
        if ($entity->currentProcess) {
            throw new \Exception('Cannot start process. Entity already has a process running.');
        }

        // Load process definition from its key.
        if (is_string($processDefinition)) {
            $processDefinition = ProcessDefinition::getByKey($processDefinition)->firstOrFail();
        }

        // Generate a random string used as a token for webservice authentication calls from Camunda.
        $authToken = str_random(24);
        $entityType = $entity::getEntityType();
        $user = auth()->user();

        // Start process in Camunda.
        $engineProcessInstanceId = $this->camunda->processes()->start([
            'key'          => $processDefinition->name_key,
            'business_key' => "$entityType:{$entity->id}",
            'variables'    => [
                'authToken'     => ['value' => $authToken, 'type' => 'String'],
                'owner'         => ['value' => $entity->organizationalUnit->name_key, 'type' => 'String'],
                'stateEntity'   => ['value' => $entity->state->name_key, 'type' => 'String']
            ]
        ])->id;

        // Wrap the creation process within a database transaction
        // to ensure easy rollback in case something goes wrong.
        DB::beginTransaction();

        try {
            // Create process instance.
            $this->processInstance = new ProcessInstance([
                'entity_type'                => $entityType,
                'entity_id'                  => $entity->id,
                'entity_previous_state_id'   => $entity->state_id,
                'process_definition_id'      => $processDefinition->id,
                'engine_process_instance_id' => $engineProcessInstanceId,
                'engine_auth_token'          => $authToken,
                'created_by'                 => $user->id,
                'updated_by'                 => $user->id,
            ]);

            // Resolve all process states (entity, process instance, steps, and forms) from Camunda.
            $this->resolveStates();

            // Resolve all process tasks from Camunda.
            $this->resolveTasks();

            // Update process instance state.
            $this->processInstance->state()->associate($this->processStates['process-instance']);
            $this->processInstance->save();

            // Create process instance steps entries from process definition.
            foreach ($processDefinition['steps'] as $step) {
                $processInstanceStep = ProcessInstanceStep::create([
                    'process_step_id'     => $step->id,
                    'process_instance_id' => $this->processInstance->id,
                    'state_id'            => $this->processStates["step-{$step->name_key}"]->id,
                ]);

                // Create process instance form entries from process definition.
                foreach ($step['forms'] as $form) {
                    $processInstanceForm = ProcessInstanceForm::create([
                        'process_form_id'          => $form->id,
                        'process_instance_step_id' => $processInstanceStep->id,
                        'state_id'                 => $this->processStates["form-{$form->name_key}"]->id,
                        'organizational_unit_id'   => isset($this->processTasks[$form->name_key]) ? $this->processTasks[$form->name_key]->organizationalUnit->id : null,
                        'engine_task_id'           => isset($this->processTasks[$form->name_key]) ? $this->processTasks[$form->name_key]->id : null,
                        'created_by'               => $user->id,
                        'updated_at'               => null,
                    ]);

                    // Create an empty form data class model mapped to the process instance form (i.e. BusinessCase, ArchitecturePlan, etc.).
                    try {
                        $formData = entity($form->name_key)::create([
                            'process_instance_form_id' => $processInstanceForm->id,
                        ]);

                        // If process instance form has some form assessments, create them.
                        if (! empty($form['assessments'])) {
                            $formAssessmentIds = [];
                            foreach ($form['assessments'] as $assessment) {
                                $formAssessmentIds[] = ProcessInstanceFormAssessment::create([
                                    'entity_type'           => $form->name_key,
                                    'entity_id'             => $formData->id,
                                    'assessed_process_form' => $assessment->assessed_process_form,
                                ])->id;
                            }
                        }
                    }
                    // Since form data classes are not all yet implemented, ignore this exception.
                    catch (ModelNotFoundException $e) {
                    }
                }
            }

            // Link process instance to the entity.
            $entity->currentProcess()->associate($this->processInstance);
            $entity->updatedBy()->associate($user);
            $entity->save();
        }
        // Rollback transaction if any exceptions occurs and cancel process instance in Camunda.
        catch (\Exception $e) {
            DB::rollBack();
            $this->camunda->processes()->delete($engineProcessInstanceId);
            throw $e;
        }

        DB::commit();
        return $this;
    }

    /**
     * Return current process instance being loaded.
     *
     * @return ProcessInstance
     */
    public function getProcessInstance()
    {
        return $this->processInstance;
    }

    /**
     * Load a process instance to work with.
     *
     * @param  mixed $processInstance
     * @return $this
     */
    public function load($processInstance)
    {
        // Load process instance from id.
        if (is_numeric($processInstance)) {
            $processInstance = ProcessInstance::findOrFail($processInstance);
        }
        // Load process instance from engine process instance id.
        elseif (is_string($processInstance)) {
            $processInstance = ProcessInstance::where('engine_process_instance_id', $processInstance)->firstOrFail();
        }
        // Ensure argument passed is an instance of process instance model.
        elseif (! $processInstance instanceof ProcessInstance) {
            throw new \Exception('Could not load process instance. Invalid argument provided.');
        }

        $this->processInstance = $processInstance;

        return $this;
    }

    /**
     * Update all process instance states and tasks from Camunda process data.
     *
     * @return $this
     */
    public function updateProcessInstance()
    {
        if (is_null($this->processInstance)) {
            throw new \Exception('Cannot update process, you must first load a process instance.');
        }

        // Resolve states and tasks from Camunda.
        $this->resolveStates();
        $this->resolveTasks();

        // Update process instance state.
        $this->processInstance->state()->associate($this->processStates['process-instance']);
        $this->processInstance->save();

        // Update process instance steps state.
        foreach ($this->processInstance->steps as $step) {
            $step->state()->associate($this->processStates["step-{$step->definition->name_key}"]);
            $step->save();

            // Update process instance forms state, task and candidate organizational unit.
            foreach ($step->forms as $form) {
                $formNameKey = $form->definition->name_key;
                $form->state()->associate($this->processStates["form-$formNameKey"]);
                if (isset($this->processTasks[$formNameKey])) {
                    $form->organizationalUnit()->associate($this->processTasks[$formNameKey]->organizationalUnit);
                    $form->engine_task_id = $this->processTasks[$formNameKey]->id;
                } else {
                    $form->engine_task_id = null;
                }
                $form->timestamps = false;
                $form->save();
            }
        }

        // Fetch entity associated with process instance.
        $entity = entity($this->processInstance->entity_type)->findOrFail($this->processInstance->entity_id);

        // If process instance is no longer active in Camunda, remove mapping with entity.
        if ($this->processStates['process-instance']->name_key !== 'active') {
            $entity->currentProcess()->dissociate();
        }

        // Update entity state.
        $entity->state()->associate($this->processStates['entity']);
        $entity->save();

        // Handle process instance completed logic.
        if ($this->processStates['process-instance']->name_key === 'completed') {
            $this->completeProcessInstance();
        }

        return $this;
    }

    /**
     * Attach all process form data entities to their parent entity.
     * i.e. Attach BusinessCase, ArchitecturePlan, etc. to Project.
     *
     * @return $this
     */
    public function attachFormData()
    {
        if (is_null($this->processInstance)) {
            throw new \Exception('Cannot attach form data, you must first load a process instance.');
        }

        // Resolve entity tied to process instance.
        $entity = entity($this->processInstance->entity_type)
            ->findOrFail($this->processInstance->entity_id);

        // Resolve and associate form data entities.
        $this->resolveProcessInstanceFormsData()->processInstanceFormsData->each(function($formData, $key) use ($entity) {
            $entity->{camel_case($key)}()->associate($formData);
        });
        $entity->save();

        return $this;
    }

    /**
     * Fire a process completed event for entities to respond for.
     * Event class is formed from process definition name (i.e. ProcessProjectApprovalCompleted).
     *
     * @return $this
     */
    protected function completeProcessInstance()
    {
        $processName = studly_case($this->processInstance->definition->name_key);
        $eventClass = "App\Events\Process{$processName}Completed";
        if (class_exists($eventClass)) {
            event(new $eventClass($this->processInstance));
        }

        return $this;
    }

    /**
     * Resolve all process instance forms data entities
     * keyed by their form definition name key.
     *
     * @return $this
     */
    protected function resolveProcessInstanceFormsData()
    {
        $this->processInstanceFormsData = collect([]);

        // Loop through each process steps and forms.
        $this->processInstance->steps->each(function ($step) {
            $step->forms->each(function ($form) {
                $formKey = $form->definition->name_key;

                // Resolve form data entity using form name key.
                $this->processInstanceFormsData[$formKey] = entity($formKey)
                    ->where('process_instance_form_id', $form->id)
                    ->firstOrfail();
            });
        });

        return $this;
    }

    /**
     * Retrieve and map Camunda process state variables to state model instances.
     *
     * @return $this
     */
    protected function resolveStates()
    {
        // Retrieve all states and re-key collection for easier reference during mapping.
        $states = State::all()->keyBy(function($item) {
            return $item->entity_type . '.' . $item->name_key;
        });

        // Retrieve process variables from Camunda.
        $processVariables = $this->camunda->processes()->getVariables($this->processInstance->engine_process_instance_id);

        // Filter through variables and only keep those related to process states.
        collect($processVariables)->filter(function($variable) {
            return starts_with($variable->name, 'state');
        })
        // Map Camunda state variables to a state model instance.
        ->each(function($variable) use ($states) {
            $state = str_replace_first('state-', '', kebab_case($variable->name));
            switch (true) {
                case $state == 'entity':
                    $key = "{$this->processInstance->entity_type}.{$variable->value}";
                    break;
                case starts_with($state, 'step'):
                    $key = "process-step.{$variable->value}";
                    break;
                case starts_with($state, 'form'):
                    $key = "process-form.{$variable->value}";
                    break;
                case $state == 'process-instance':
                    $key = "process-instance.{$variable->value}";
                    break;
                default;
                    $key = null;
            }

            if ($key && ($stateModelInstance = $states[$key] ?? null)) {
                $this->processStates[$state] = $stateModelInstance;
            }
        });

        return $this;
    }

    /**
     * Retrieve and map Camunda process tasks to their appropriate forms.
     *
     * @return $this
     */
    protected function resolveTasks()
    {
        // Retrieve all organizational units and re-key collection for easier reference during mapping.
        $organizationalUnits = OrganizationalUnit::all()->keyBy(function ($item) {
            return $item->name_key;
        });

        // Get all tasks for current process.
        $tasks = $this->camunda->tasks()->for($this->processInstance);
        foreach ($tasks as $task) {
            // Since it's technically possible to have multiple candidate groups
            // for a same task we only get the first one.
            $organizationalUnitKey = collect($this->camunda->tasks()->getCandidates($task->id))
                ->filter(function ($item, $key) {
                    return $item->type == 'candidate' && $item->groupId !== null;
                })
                ->first()
                ->groupId;

            $task->organizationalUnit = $organizationalUnits[$organizationalUnitKey];
            $this->processTasks[$task->formKey] = $task;
        }

        return $this;
    }
}
