<?php

namespace App\Models\LearningProduct\Development;

use App\Models\Process\ProcessInstanceFormDataModel;

class OfferingAndEnrolmentEstimates extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'comments',
        'estimated_average_annual_participant_number',
        'process_instance_form_id',
        'process_instance_id',
    ];

    protected $with = [
        'offeringRegions',
    ];

    public function offeringRegions()
    {
        return $this->hasMany(OfferingRegion::class);
    }

    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'offeringRegions',
        ]);

        parent::saveFormData($data);
    }
}
