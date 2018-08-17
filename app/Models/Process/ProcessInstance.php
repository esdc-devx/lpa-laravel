<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessInstance extends BaseModel
{
    use SoftDeletes, UsesUserAudit;

    protected $hidden = ['process_definition_id', 'state_id', 'engine_auth_token'];

    public function entity()
    {
        return $this->morphTo();
    }

    public function definition()
    {
        return $this->belongsTo(ProcessDefinition::class, 'process_definition_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function steps()
    {
        return $this->hasMany(ProcessInstanceStep::class);
    }

    public function scopeWithProcessDetails()
    {
        return $this->with([
            'definition', 'state', 'createdBy', 'updatedBy',
            'steps', 'steps.definition', 'steps.state',
            'steps.forms.definition', 'steps.forms.state', 'steps.forms.currentEditor', 'steps.forms.createdBy', 'steps.forms.updatedBy'
        ]);
    }
}
