<?php

namespace App\Http\Requests;

class ArchitecturePlanAssessmentFormRequest extends ProcessInstanceFormDataRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->submitted()) {
            return [
                'assessment_date'                => 'required|date|before_or_equal:today',
                'is_process_cancelled'           => 'required|boolean',
                'process_cancellation_rationale' => 'required_if:is_process_cancelled,true|string|nullable|max:2500',
                'assessments.*.comments'         => 'required_if:assessments.*.process_form_decision_id,2',
            ];
        }

        return [];
    }
}
