<?php

namespace App\Process;

use App\Camunda\Camunda;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessInstanceStep;
use App\Models\State;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ProcessManager {

    protected $camunda;
    protected $processInstance;
    protected $processStates = [];
    protected $processTasks = [];

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

                    // Create an empty data class model mapped to the process instance form (i.e. BusinessCase, ArchitecturePlan, etc.).
                    try {
                        entity($form->name_key)::create([
                            'process_instance_form_id' => $processInstanceForm->id,
                        ]);
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
            throw new \Exception('Cannot resolve process states, you must first load a process.');
        }

        // Resolve states and tasks from Camunda.
        $this->resolveStates();
        $this->resolveTasks();

        // Update process instance state.
        $this->processInstance->state()->associate($this->processStates['process-instance']);
        $this->processInstance->save();

        // Update entity state.
        $entity = entity($this->processInstance->entity_type)->findOrFail($this->processInstance->entity_id);
        $entity->state()->associate($this->processStates['entity']);
        $entity->save();

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

        return $this;
    }

    /**
     * Retrieve and map Camunda process state variables to state model instances.
     *
     * @return $this
     */
    protected function resolveStates()
    {
        if (is_null($this->processInstance)) {
            throw new \Exception('Cannot resolve process states, you must first load a process.');
        }

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
            }
            $this->processStates[$state] = $states[$key];
        });

        // Resolve and format process instance state from Camunda.
        $processInstanceState = $this->camunda->processes()->getInstance($this->processInstance->engine_process_instance_id)->state;
        $processInstanceState = strtolower(str_replace('_', '-', $processInstanceState));

        // Map Camunda process instance state to its state model instance.
        $this->processStates['process-instance'] = $states["process-instance.$processInstanceState"];

        return $this;
    }

    /**
     * Retrieve and map Camunda process tasks to their appropriate forms.
     *
     * @return $this
     */
    protected function resolveTasks()
    {
        if (is_null($this->processInstance)) {
            throw new \Exception('Cannot resolve process tasks, you must first load a process.');
        }

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
