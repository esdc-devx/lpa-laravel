<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class Risk extends BaseModel
{
    protected $hidden = ['pivot'];

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
