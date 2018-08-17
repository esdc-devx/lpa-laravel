<?php

namespace App\Models\Project\BusinessCase;

use App\Models\Community;
use App\Models\Process\ProcessInstanceFormDataModel;

class BusinessCase extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id', 'request_source_other', 'business_issue', 'is_required_training', 'learning_response_strategy',
        'potential_solution_type_other', 'expected_annual_participant_number', 'timeframe_id', 'timeframe_rationale', 'cost_center',
        'maintenance_fund_id', 'maintenance_fund_rationale', 'salary_fund_id', 'salary_fund_rationale', 'comment', 'internal_resource_other',
    ];

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        // Multiple choice lists.
        'requestSources', 'potentialSolutionTypes', 'governmentPriorities', 'communities', 'internalResources',
        // Complex data.
        'departmentalBenefits', 'learnersBenefits', 'risks',
    ];

    public function requestSources()
    {
        return $this->belongsToMany(RequestSource::class);
    }

    public function potentialSolutionTypes()
    {
        return $this->belongsToMany(PotentialSolutionType::class);
    }

    public function governmentPriorities()
    {
        return $this->belongsToMany(GovernmentPriority::class);
    }

    public function timeframe()
    {
        return $this->belongsTo(Timeframe::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class);
    }

    public function departmentalBenefits()
    {
        return $this->hasMany(DepartmentalBenefit::class);
    }

    public function learnersBenefits()
    {
        return $this->hasMany(LearnersBenefit::class);
    }

    public function maintenanceFund()
    {
        return $this->belongsTo(MaintenanceFund::class);
    }

    public function salaryFund()
    {
        return $this->belongsTo(SalaryFund::class);
    }

    public function internalResources()
    {
        return $this->belongsToMany(InternalResource::class);
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelationships($data, [
            'requestSources',
            'potentialSolutionTypes',
            'governmentPriorities',
            'communities',
            'internalResources',
        ]);

        $this->syncRelatedModels($data, [
            'departmentalBenefits',
            'learnersBenefits',
            'risks',
        ]);

        parent::saveFormData($data);
    }
}
