<?php

namespace App\Models\Project;

use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessInstance;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes, UsesUserAudit;

    protected $hidden = ['organizational_unit_id', 'state_id', 'business_case_id', 'process_instance_id'];
    protected $dates = ['deleted_at'];

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function currentProcess()
    {
        return $this->belongsTo(ProcessInstance::class, 'process_instance_id');
    }

    public function businessCase()
    {
        return $this->hasOne(BusinessCase::class);
    }
}
