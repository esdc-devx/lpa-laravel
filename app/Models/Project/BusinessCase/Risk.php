<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class Risk extends BaseModel
{
    protected $fillable = [
        'business_case_id', 'risk_type_id', 'risk_impact_level_id', 'risk_probability_level_id', 'rationale'
    ];

    public $timestamps = false;

    public function riskType()
    {
        return $this->belongsTo(RiskType::class);
    }

    public function riskImpactLevel()
    {
        return $this->belongsTo(RiskImpactLevel::class);
    }

    public function riskProbabilityLevel()
    {
        return $this->belongsTo(RiskProbabilityLevel::class);
    }
}
