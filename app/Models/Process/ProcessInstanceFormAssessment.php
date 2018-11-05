<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\Process\ProcessFormDecision;

class ProcessInstanceFormAssessment extends BaseModel
{
    protected $hidden = ['entity_type', 'entity_id'];

    public $timestamps = false;

    public function entity()
    {
        return $this->morphTo();
    }

    public function decision()
    {
        return $this->belongsTo(ProcessFormDecision::class, 'process_form_decision_id');
    }
}
