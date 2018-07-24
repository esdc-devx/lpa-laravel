<?php

namespace App\Models\Project\BusinessCase;

use App\Models\BaseModel;

class DepartmentalBenefit extends BaseModel
{
    protected $hidden = ['pivot'];

    public $timestamps = false;

    public function departmentalBenefitType()
    {
        return $this->belongsTo(DepartmentalBenefitType::class);
    }
}
