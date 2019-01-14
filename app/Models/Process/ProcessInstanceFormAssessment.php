<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\Process\ProcessFormDecision;

class ProcessInstanceFormAssessment extends BaseModel
{
    protected $fillable = [
        'process_instance_form_id',
        'entity_type',
        'entity_id',
        'assessed_process_form',
        'process_form_decision_id',
        'comments',
    ];

    protected $with = [
        'assessedProcessFormDefinition',
        'decision',
    ];

    public $timestamps = false;

    public function entity()
    {
        return $this->morphTo();
    }

    public function assessedProcessFormDefinition()
    {
        return $this->belongsTo(ProcessForm::class, 'assessed_process_form', 'name_key');
    }

    public function decision()
    {
        return $this->belongsTo(ProcessFormDecision::class, 'process_form_decision_id');
    }
}
