<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Community;
use App\Models\Process\ProcessInstanceForm;

class BusinessCase extends BaseModel
{
    protected $hidden = ['process_instance_form_id'];

    public $timestamps = false;

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        'requestSources', 'potentialSolutionTypes', 'governmentPriorities', 'timeframe', 'communities',
        'departmentalBenefits', 'learnersBenefits', 'maintenanceFund', 'salaryFund', 'internalResources', 'risks',
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
        return $this->belongsToMany(DepartmentalBenefit::class)
            ->with('departmentalBenefitType');
    }

    public function learnersBenefits()
    {
        return $this->belongsToMany(LearnersBenefit::class)
            ->with('learnersBenefitType');
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
        return $this->belongsToMany(Risk::class)
            ->with(['riskType', 'riskImpactLevel', 'riskProbabilityLevel']);
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
        return $this->load($this->relationships);
    }
}
