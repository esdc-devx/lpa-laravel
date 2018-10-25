<?php

namespace App\Http\Requests;

use App\Models\Process\ProcessFormDecision;

class GateOneApprovalFormRequest extends ProcessInstanceFormDataRequest
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
                'assessment_date'               => 'required|date|before_or_equal:today',
                'is_entity_cancelled'           => 'required|boolean',
                'entity_cancellation_rationale' => 'required_if:is_entity_cancelled,true|string|nullable|max:2500',
                'assessments.*.comments'        => 'required_if:assessments.*.process_form_decision_id,' . ProcessFormDecision::getByKey('rejected')->first()->id,
            ];
        }

        return [];
    }
}
