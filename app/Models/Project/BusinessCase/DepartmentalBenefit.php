<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class DepartmentalBenefit extends BaseModel
{
    protected $fillable = [
        'business_case_id', 'departmental_benefit_type_id', 'departmental_benefit_type_other', 'rationale',
    ];

    public $timestamps = false;

    public function departmentalBenefitType()
    {
        return $this->belongsTo(DepartmentalBenefitType::class);
    }
}
