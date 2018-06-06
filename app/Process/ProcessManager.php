<?php

namespace App\Process;

use App\Camunda\Camunda;
use App\Models\State;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessInstanceStep;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class ProcessManager {

    protected $camunda;
    protected $processInstance;
    protected $engineProcessInstanceId;
    protected $entityType;
    protected $processStates = [];

    public function __construct(Camunda $camunda)
    {
        $this->camunda = $camunda;
    }

    /**
     * Start a process instance.
     *
     * @param  mixed $processDefinition
     * @param  Illuminate\Database\Eloquent\Model $entity
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

        $this->entityType = $entity::$entityType;
        // Generate a random string used as a token for webservice authentication calls from Camunda.
        $authToken = str_random(24);
        $processState = State::getByKey('process-instance.running')->first();
        $user = auth()->user();

        // Start process in Camunda.
        $this->engineProcessInstanceId = $this->camunda->processes()->start([
            'key'          => $processDefinition->name_key,
            'business_key' => "{$this->entityType}:{$entity->id}",
            'variables'    => [
                'authToken' => ['value' => $authToken, 'type' => 'String'],
                'owner'     => ['value' => $entity->organizationalUnit->name_key, 'type' => 'String']
            ]
        ])->id;

        // Resolve all process states (entity, steps, and forms) from Camunda.
        $this->resolveStates();

        // Wrap the creation process within a database transaction
        // to ensure easy rollback in case something goes wrong.
        DB::beginTransaction();

        try {
            // Create process instance.
            $processInstance = ProcessInstance::create([
                'entity_type'                => $this->entityType,
                'entity_id'                  => $entity->id,
                'entity_previous_state_id'   => $entity->state_id,
                'process_definition_id'      => $processDefinition->id,
                'engine_process_instance_id' => $this->engineProcessInstanceId,
                'engine_auth_token'          => $authToken,
                'state_id'                   => $processState->id,
                'created_by'                 => $user->id,
                'updated_by'                 => $user->id,
            ]);

            // Create process instance steps entries from process definition.
            foreach ($processDefinition['steps'] as $step) {
                $processInstanceStep = ProcessInstanceStep::create([
                    'process_step_id'     => $step->id,
                    'process_instance_id' => $processInstance->id,
                    'state_id'            => $this->processStates["step-{$step->name_key}"]->id,
                ]);

                // Create process instance form entries from process definition.
                foreach ($step['forms'] as $form) {
                    $formInstance = ProcessInstanceForm::create([
                        'process_form_id'          => $form->id,
                        'process_instance_step_id' => $processInstanceStep->id,
                        'state_id'                 => $this->processStates["form-{$form->name_key}"]->id,
                        'created_by'               => $user->id,
                        'updated_at'               => null,
                    ]);
                    // Create an empty data class model mapped to the process instance form (i.e. BusinessCase, ArchitecturePlan, etc.).
                    if ($businessObject = Relation::getMorphedModel($form->name_key)) {
                        $businessObject::create([
                            'process_instance_form_id' => $formInstance->id,
                        ]);
                    }
                }
            }

            // Link process instance to the entity.
            $entity->currentProcess()->associate($processInstance);
            $entity->updatedBy()->associate($user);
            $entity->save();
        }
        // Rollback transaction if any exceptions occurs and cancel process instance in Camunda.
        catch (\Exception $e) {
            DB::rollBack();
            $this->camunda->processes()->delete($this->engineProcessInstanceId);
            throw $e;
        }

        DB::commit();
        $this->processInstance = $processInstance;
        return $this;
    }

    public function getProcessInstance()
    {
        return $this->processInstance;
    }

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

        $this->processInstance = $processInstance;
        $this->engineProcessInstanceId = $processInstance->engine_process_instance_id;
        $this->entityType = $processInstance->entity_type;

        return $this;
    }

    /**
     * Retrieve and map Camunda process state variables to state model instances.
     *
     * @return $this
     */
    protected function resolveStates()
    {
        if (is_null($this->engineProcessInstanceId)) {
            throw new \Exception('Cannot resolve process states, you must first load a process.');
        }

        // Retrieve all states and re-key collection for easier reference during mapping.
        $states = State::all()->keyBy(function($item) {
            return $item->entity_type . '.' . $item->name_key;
        });

        // Retrieve process variables from Camunda.
        $processVariables = $this->camunda->processes()->getVariables($this->engineProcessInstanceId);

        // Filter through variables and only keep those related to process states.
        collect($processVariables)->filter(function($variable) {
            return starts_with($variable->name, 'state');
        })
        // Map Camunda state variables to a state model instance.
        ->each(function($variable) use ($states) {
            $state = str_replace_first('state-', '', kebab_case($variable->name));
            switch (true) {
                case $state == 'entity':
                    $key = "{$this->entityType}.{$variable->value}";
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

        return $this;
    }
}
