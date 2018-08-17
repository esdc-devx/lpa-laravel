<?php

namespace App\Models\Project;

use App\Events\ProcessEntityDeleted;
use App\Models\BaseModel;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessInstance;
use App\Models\Project\ArchitecturePlan\ArchitecturePlan;
use App\Models\Project\ArchitecturePlan\ArchitecturePlanAssessment;
use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\BusinessCase\BusinessCaseAssessment;
use App\Models\State;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes, UsesUserAudit;

    protected $hidden = [
        'organizational_unit_id', 'state_id', 'business_case_id', 'process_instance_id'
    ];
    protected $dates = [
        'deleted_at'
    ];
    protected $dispatchesEvents = [
        'deleted' => ProcessEntityDeleted::class,
    ];

    public function organizationalUnit()
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function processInstances()
    {
        return $this->morphMany(ProcessInstance::class, 'entity');
    }

    public function currentProcess()
    {
        return $this->belongsTo(ProcessInstance::class, 'process_instance_id');
    }

    public function businessCase()
    {
        return $this->belongsTo(BusinessCase::class);
    }

    public function businessCaseAssessment()
    {
        return $this->belongsTo(BusinessCaseAssessment::class);
    }

    public function architecturePlan()
    {
        return $this->belongsTo(ArchitecturePlan::class);
    }

    public function architecturePlanAssessment()
    {
        return $this->belongsTo(ArchitecturePlanAssessment::class);
    }
}
