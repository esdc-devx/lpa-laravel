<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class LearnersBenefit extends BaseModel
{
    protected $hidden = ['pivot'];

    public $timestamps = false;

    public function learnersBenefitType()
    {
        return $this->belongsTo(LearnersBenefitType::class);
    }
}
