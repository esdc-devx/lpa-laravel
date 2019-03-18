<?php

namespace App\Models\LearningProduct\Development;

use App\Models\BaseModel;
use App\Models\Lists\Region;

class OfferingRegion extends BaseModel
{
    protected $fillable = [
        'offering_and_enrolment_estimates_id',
        'region_id',
        'regional_annual_bilingual_offering_number',
        'regional_annual_english_offering_number',
        'regional_annual_french_offering_number',
        'regional_annual_simultaneous_interpretation_offering_number',
    ];

    protected $with = [
        'region',
    ];

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
