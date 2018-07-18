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
        'departmentalBenefits', 'departmentalBenefits.departmentalBenefitType',
        'learnersBenefits', 'learnersBenefits.learnersBenefitType',
    ];

    public function processInstanceForm()
    {
        return $this->belongsTo(ProcessInstanceForm::class)->with('state', 'organizationalUnit', 'currentEditor', 'updatedBy');
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

    public function saveFormData(array $data)
    {
        $this->syncRelationships($data, [
            'requestSources',
            'potentialSolutionTypes',
            'governmentPriorities',
            'communities',
        ]);

        $this->syncRelatedModels($data, [
            'departmentalBenefits',
            'learnersBenefits',
        ]);

        // Update properties on business case.
        $this->update($data);

        // Return model with all of its updated relationships.
        return $this->load($this->relationships);
    }
}
