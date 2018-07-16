<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Community;
use App\Models\Process\ProcessInstanceForm;

class BusinessCase extends BaseModel
{
    protected $hidden = ['process_instance_form_id', 'timeframe_id'];

    public $timestamps = false;

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        'processInstanceForm', 'processInstanceForm.definition', 'processInstanceForm.organizationalUnit', 'processInstanceForm.currentEditor',
        'requestSources', 'potentialSolutionTypes', 'governmentPriorities', 'timeframe', 'communities'
    ];

    public function processInstanceForm()
    {
        return $this->belongsTo(ProcessInstanceForm::class)->with('state', 'currentEditor', 'updatedBy');
    }

    public function requestSources()
    {
        return $this->belongsToMany(RequestSource::class);
    }

    public function potentialSolutionTypes()
    {
        return $this->belongsToMany(PotentialSolutionType::class);
    }

    public function governmentPriorities()
    {
        return $this->belongsToMany(GovernmentPriority::class);
    }

    public function timeframe()
    {
        return $this->belongsTo(Timeframe::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class);
    }

    public function saveFormData(array $data)
    {
        if (isset($data['request_sources'])) {
            $this->requestSources()->sync($data['request_sources']);
        }

        if (isset($data['potential_solution_types'])) {
            $this->potentialSolutionTypes()->sync($data['potential_solution_types']);
        }

        if (isset($data['government_priorities'])) {
            $this->governmentPriorities()->sync($data['government_priorities']);
        }

        if (isset($data['communities'])) {
            $this->communities()->sync($data['communities']);
        }

        $this->request_source_other = $data['request_source_other'] ?? null;
        $this->business_issue = $data['business_issue'] ?? null;
        $this->learning_response_strategy = $data['learning_response_strategy'] ?? null;
        $this->potential_solution_type_other = $data['potential_solution_type_other'] ?? null;
        $this->is_required_training = $data['is_required_training'] ?? null;
        $this->timeframe_id = $data['timeframe'] ?? null;
        $this->timeframe_rationale = $data['timeframe_rationale'] ?? null;
        $this->expected_annual_participant_number = $data['expected_annual_participant_number'] ?? null;
        $this->save();

        // Update process instance form audit and timestamps.
        $this->processInstanceForm->updateAudit();

        // Update process instance audit and timestamps.
        $processInstance = $this->processInstanceForm->step->processInstance->updateAudit();

        // Update entity audit and timestamps.
        $entity = entity($processInstance->entity_type)
            ->find($processInstance->entity_id)
            ->updateAudit();

        // Return model with all of its updated relationships.
        return $this->load($this->relationships);
    }

    // @note: Move to a re-usable trait?
    public function scopeFromProcessInstanceId($query, $id) {
        return $query->select($this->getTable() . '.*')
            ->join('process_instance_forms', $this->getTable() . '.process_instance_form_id', '=', 'process_instance_forms.id')
            ->join('process_instance_steps', 'process_instance_forms.process_instance_step_id', '=', 'process_instance_steps.id')
            ->join('process_instances', 'process_instance_steps.process_instance_id', '=', 'process_instances.id')
            ->where('process_instances.id', $id);
    }
}
