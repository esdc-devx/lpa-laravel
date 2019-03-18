<?php

namespace App\Models\LearningProduct\Design;

use App\Models\BaseModel;

class ApplicablePolicy extends BaseModel
{
    protected $fillable = [
        'design_id',
        'name',
    ];

    public $timestamps = false;
}
