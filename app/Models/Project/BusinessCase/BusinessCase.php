<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Process\ProcessInstanceForm;

class BusinessCase extends BaseModel
{
    protected $hidden = ['process_instance_form_id'];

    public $timestamps = false;

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        'processInstanceForm', 'processInstanceForm.definition', 'processInstanceForm.currentEditor',
        'requestSources', 'potentialSolutionTypes', 'governmentPriorities'
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

    // @note: Move to a re-usable trait?
    public function scopeFromProcessInstanceId($query, $id) {
        return $query->select($this->getTable() . '.*')
            ->join('process_instance_forms', $this->getTable() . '.process_instance_form_id', '=', 'process_instance_forms.id')
            ->join('process_instance_steps', 'process_instance_forms.process_instance_step_id', '=', 'process_instance_steps.id')
            ->join('process_instances', 'process_instance_steps.process_instance_id', '=', 'process_instances.id')
            ->where('process_instances.id', $id);
    }
}
