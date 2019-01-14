<?php

namespace App\Models\Process;

use App\Models\BaseModel;
use App\Models\State;
use App\Models\Traits\HasInformationSheets;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProcessInstance extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasInformationSheets;

    protected $fillable = [
        'process_definition_id',
        'entity_type',
        'entity_id',
        'engine_process_instance_id',
        'engine_auth_token',
        'send_notifications',
        'entity_previous_state_id',
        'state_id',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'engine_auth_token',
    ];

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

    public function forms()
    {
        return $this->hasManyThrough(ProcessInstanceForm::class, ProcessInstanceStep::class);
    }

    public function scopeWithProcessDetails()
    {
        return $this->with([
            'definition', 'state', 'createdBy', 'updatedBy',
            'steps', 'steps.definition', 'steps.state', 'steps.forms',
        ]);
    }
}
