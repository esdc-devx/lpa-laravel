<?php

namespace App\Models\Project;

use App\Models\OrganizationalUnit\OrganizationalUnit;
use App\Models\Process\ProcessInstance;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes, UsesUserAudit;

    public static $entityType = 'project';

    protected $guarded = [];
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
