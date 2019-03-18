<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;

class GuestSpeaker extends BaseModel
{
    protected $fillable = [
        'operational_details_id',
        'required_profile_en',
        'required_profile_fr',
        'schedule_en',
        'schedule_fr',
    ];

    public $timestamps = false;
}
