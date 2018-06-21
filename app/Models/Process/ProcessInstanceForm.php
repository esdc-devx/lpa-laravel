<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;

class ProcessInstanceForm extends BaseModel
{
    use UsesUserAudit;

    protected $hidden = ['process_form_id', 'process_instance_id', 'state_id', 'process_instance_step_id', 'created_at', 'created_by'];

    public function definition()
    {
        return $this->belongsTo(ProcessForm::class, 'process_form_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currentEditor()
    {
        return $this->belongsTo(User::class, 'current_editor');
    }

    public function step()
    {
        return $this->belongsTo(ProcessInstanceStep::class);
    }
}
