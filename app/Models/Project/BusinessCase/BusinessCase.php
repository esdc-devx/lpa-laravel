<?php

namespace App\Models\Project\BusinessCase;

use App\Models\Lists\Community;
use App\Models\Lists\DepartmentalResultsFrameworkIndicator;
use App\Models\Lists\RequestOrigin;
use App\Models\Lists\SchoolPriority;
use App\Models\Process\ProcessInstanceFormDataModel;

class BusinessCase extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'business_issue',
        'comments',
        'cost_centre',
        'expected_annual_participant_number',
        'is_required_training',
        'long_term_learning_response',
        'medium_term_learning_response',
        'other_operational_considerations',
        'process_instance_form_id',
        'process_instance_id',
        'request_origins_other',
        'short_term_learning_response',
    ];

    protected $with = [
        'communities',
        'departmentalResultsFrameworkIndicators',
        'requestOrigins',
        'risks',
        'schoolPriorities',
        'spendings',
    ];

    protected $casts = [
        'is_required_training' => 'boolean',
    ];

    public function requestOrigins()
    {
        return $this->belongsToMany(RequestOrigin::class);
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
            'requestOrigins',
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
