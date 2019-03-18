<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;
use App\Models\Lists\RiskType;

class Risk extends BaseModel
{
    protected $fillable = [
        'business_case_id',
        'risk_type_id',
        'risk_type_other',
        'rationale',
    ];

    protected $with = [
        'riskType',
    ];

    public $timestamps = false;

    public function riskType()
    {
        return $this->belongsTo(RiskType::class);
    }
}
