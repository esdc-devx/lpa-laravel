<?php

namespace App\Models\Project;

use App\Events\ProcessEntityDeleted;
use App\Models\BaseModel;
use App\Models\LearningProduct\LearningProduct;
use App\Models\Project\ArchitecturePlan\ArchitecturePlan;
use App\Models\Project\ArchitecturePlan\ArchitecturePlanAssessment;
use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\BusinessCase\BusinessCaseAssessment;
use App\Models\State;
use App\Models\Traits\HasProcesses;
use App\Models\Traits\UsesUserAudit;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes, UsesUserAudit, HasProcesses;

    protected $hidden = [
        'organizational_unit_id', 'state_id', 'process_instance_id',
        'business_case_id', 'business_case_assessment_id', 'architecture_plan_id', 'architecture_plan_assessment_id',
    ];

    protected $dates = [
        'deleted_at'
    ];

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

    public function learningProducts()
    {
        return $this->hasMany(LearningProduct::class);
    }
}
