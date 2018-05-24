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
            throw new \Exception("Cannot start [{$processDefinitionKey }]. Entity already has a process running.");
        }

        // Load process definition from its key.
        if (is_string($processDefinition)) {
            $processDefinition = ProcessDefinition::getByKey($processDefinitionKey)->first();
        }

        // Generate a random string used as a token for webservice authentication calls from Camunda.
        $authToken = str_random(24);
        $processState = State::getByKey('process-instance.running')->first();
        $stepState = State::getByKey('process-step.locked')->first();
        $formState = State::getByKey('process-form.locked')->first();
        $user = auth()->user();

        // Start process in Camunda.
        $engineProcessInstanceId = $this->camunda->as($user)->processes()->start([
            'key'          => $processDefinition->name_key,
            'business_key' => class_basename(get_class($entity)) . ':' . $entity->id,
            'variables'    => [
                'authToken' => ['value' => $authToken, 'type' => 'String'],
                'owner'     => ['value' => $entity->organizationalUnit->name_key, 'type' => 'String']
            ]
        ])->id;

        // Wrap the creation process within a database transaction
        // to ensure easy rollback in case something goes wrong.
        DB::beginTransaction();

        try {
            // Create process instance.
            $processInstance = ProcessInstance::create([
                'entity_type'                => $entity::$entity_type,
                'entity_id'                  => $entity->id,
                'entity_previous_state_id'   => $entity->state_id,
                'process_definition_id'      => $processDefinition->id,
                'engine_process_instance_id' => $engineProcessInstanceId,
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
                    'state_id'            => $stepState->id,
                ]);

                // Create process instance form entries from process definition.
                foreach ($step['forms'] as $form) {
                    $formInstance = ProcessInstanceForm::create([
                        'process_form_id'          => $form->id,
                        'process_instance_step_id' => $processInstanceStep->id,
                        'state_id'                 => $formState->id,
                        'created_by'               => $user->id,
                        'updated_by'               => $user->id,
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
            $this->camunda->processes()->delete($engineProcessInstanceId);
            throw $e;
        }

        DB::commit();
        return $processInstance;
    }
}
