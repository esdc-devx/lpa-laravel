<?php

namespace App\Models\Project\BusinessCase;

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormDataModel;

class BusinessCaseAssessment extends ProcessInstanceFormDataModel
{
    protected $fillable = ['process_instance_form_id', 'is_process_cancelled', 'process_cancellation_rationale', 'assessment_date'];
    protected $casts = [
        'is_process_cancelled' => 'boolean'
    ];

    public $relationships = ['assessments'];

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
