<?php

namespace App\Models\LearningProduct\Design;

use App\Models\BaseModel;

class AdditionalCost extends BaseModel
{
    protected $fillable = [
        'design_id',
        'rationale',
        'costs',
    ];

    public $timestamps = false;
}
