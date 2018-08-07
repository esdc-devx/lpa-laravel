<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArchitecturePlanAssessmentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Defer to ProcessInstanceFormRequest authorize method.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // If form is submitted, enforce validation rules.
        if (collect($this->segments())->last() == 'submit') {
            return [
                'assessment_date'                => 'required|date|before_or_equal:today',
                'is_process_cancelled'           => 'required|boolean',
                'process_cancellation_rationale' => 'required_if:is_process_cancelled,true|string|nullable|max:2500',
                'assessments.*.comment'          => 'required_if:assessments.*.process_form_decision_id,2',
            ];
        }

        return [];
    }
}
