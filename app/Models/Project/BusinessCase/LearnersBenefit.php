<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class LearnersBenefit extends BaseModel
{
    protected $hidden = ['pivot'];
    protected $fillable = [
        'learners_benefit_type_id', 'learners_benefit_type_other', 'rationale',
    ];

    public $timestamps = false;

    public function learnersBenefitType()
    {
        return $this->belongsTo(LearnersBenefitType::class);
    }
}
