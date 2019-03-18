<?php

namespace App\Models\Process;

class ProcessInstanceFormAssessmentDataModel extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'assessment_date',
        'entity_cancellation_rationale',
        'is_entity_cancelled',
        'process_instance_form_id',
        'process_instance_id',
    ];

    protected $appends = [
        'allow_entity_cancellation',
    ];

    protected $casts = [
        'is_entity_cancelled' => 'boolean'
    ];

    protected $with = [
        'assessments',
    ];

    public function assessments()
    {
        return $this->morphMany(ProcessInstanceFormAssessment::class, 'entity');
    }

    /**
     * By default, all form assessments can cancel the process entity.
     * Override this method to change this behavior.
     *
     * @return bool
     */
    public function getAllowEntityCancellationAttribute()
    {
        return true;
    }

    /**
     * Handle save form data for all form assessments.
     *
     * @param  array $data
     * @return $this
     */
    public function saveFormData(array $data)
    {
        $this->syncRelatedModels($data, [
            'assessments',
        ]);

        parent::saveFormData($data);

        return $this;
    }
}
