<?php

namespace App\Models\LearningProduct\Design;

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormDataModel;

class DesignAssessment extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id',
        'is_entity_cancelled',
        'entity_cancellation_rationale',
        'assessment_date',
    ];

    protected $with = [
        'assessments',
    ];

    public function assessments()
    {
        return $this->morphMany(ProcessInstanceFormAssessment::class, 'entity');
    }

    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'assessments',
        ]);

        parent::saveFormData($data);
    }
}
