<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\State;

class ProcessInstanceStep extends BaseModel
{
    protected $fillable = [
        'process_step_id',
        'process_instance_id',
        'state_id',
    ];

    public function definition()
    {
        return $this->belongsTo(ProcessStep::class, 'process_step_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function processInstance()
    {
        return $this->belongsTo(ProcessInstance::class);
    }

    public function forms()
    {
        return $this->hasMany(ProcessInstanceForm::class);
    }
}
