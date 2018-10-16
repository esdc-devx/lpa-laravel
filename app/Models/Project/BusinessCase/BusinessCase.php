<?php

namespace App\Models\Project\BusinessCase;

use App\Models\Community;
use App\Models\Process\ProcessInstanceFormDataModel;

class BusinessCase extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id', 'request_source_other', 'business_issue',
        'learning_response_strategy', 'short_term_learning_response', 'medium_term_learning_response', 'long_term_learning_response', 'is_required_training',
        'expected_annual_participant_number', 'cost_centre', 'other_operational_considerations',
        'comments',
    ];

    // These relationships will be loaded when retrieving the model.
    public $relationships = [
        // Multiple choice lists.
        'requestSources', 'schoolPriorities', 'communities', 'departmentalResultsFrameworkIndicators',
        // Complex data.
        'spendings', 'risks',
    ];

    public function requestSources()
    {
        return $this->belongsToMany(RequestSource::class);
    }

    public function schoolPriorities()
    {
        return $this->belongsToMany(SchoolPriority::class);
    }

    public function communities()
    {
        return $this->belongsToMany(Community::class);
    }

    public function departmentalResultsFrameworkIndicators()
    {
        return $this->belongsToMany(DepartmentalResultsFrameworkIndicator::class);
    }

    public function spendings()
    {
        return $this->hasMany(Spending::class);
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelationships($data, [
            'requestSources',
            'schoolPriorities',
            'communities',
            'departmentalResultsFrameworkIndicators',
        ]);

        $this->syncRelatedModels($data, [
            'spendings',
            'risks',
        ]);

        parent::saveFormData($data);
    }
}
