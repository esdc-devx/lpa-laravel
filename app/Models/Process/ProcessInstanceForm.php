<?php

namespace App\Models\Process;

use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ProcessInstanceForm extends Model
{
    use UsesUserAudit;

    protected $guarded = [];
    protected $hidden = ['process_form_id', 'process_instance_id', 'state_id', 'process_instance_step_id'];

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
