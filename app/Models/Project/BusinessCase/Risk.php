<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class Risk extends BaseModel
{
    protected $fillable = [
        'business_case_id', 'risk_type_id', 'risk_type_other', 'rationale'
    ];

    public $timestamps = false;

    public function riskType()
    {
        return $this->belongsTo(RiskType::class);
    }
}
