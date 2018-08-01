<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Community;
use App\Models\Process\ProcessInstanceForm;

class BusinessCase extends BaseModel
{
    protected $hidden = ['process_instance_form_id'];

    protected $fillable = [
        'process_instance_form_id', 'request_source_other', 'business_issue', 'is_required_training', 'learning_response_strategy',
        'potential_solution_type_other', 'expected_annual_participant_number', 'timeframe_id', 'timeframe_rationale', 'cost_center',
        'maintenance_fund_id', 'maintenance_fund_rationale', 'salary_fund_id', 'salary_fund_rationale', 'comment', 'internal_resource_other',
    ];

    public $timestamps = false;

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        // Multiple choice lists.
        'requestSources', 'potentialSolutionTypes', 'governmentPriorities', 'communities', 'internalResources',
        // Complex data.
        'departmentalBenefits', 'learnersBenefits', 'risks',
    ];

    public function processInstanceForm()
    {
        return $this->belongsTo(ProcessInstanceForm::class)
            ->with('state', 'organizationalUnit', 'currentEditor', 'updatedBy');
    }

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
        return $this->belongsToMany(DepartmentalBenefit::class);
    }

    public function learnersBenefits()
    {
        return $this->belongsToMany(LearnersBenefit::class);
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
        return $this->belongsToMany(Risk::class);
    }

    public function saveFormData(array $data)
    {
        // Synchronize one to many relationships.
        $this->syncRelationships($data, [
            'requestSources',
            'potentialSolutionTypes',
            'governmentPriorities',
            'communities',
            'internalResources',
        ]);

        // Synchronize one to many relationships and also create/update/delete related models.
        $this->syncRelatedModels($data, [
            'departmentalBenefits',
            'learnersBenefits',
            'risks',
        ]);

        // Update properties on business case.
        $this->update($data);

        // Return model with all of its updated relationships.
        // and format list output to only return ids.
        return $this->load($this->relationships)->formatListsOutput();
    }
}
