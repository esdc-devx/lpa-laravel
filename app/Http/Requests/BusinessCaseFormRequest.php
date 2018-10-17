<?php

namespace App\Http\Requests;

class BusinessCaseFormRequest extends ProcessInstanceFormDataRequest
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
                'request_sources'                           => 'required_without:request_source_other|array',
                'request_source_other'                      => 'nullable|string|max:100',
                'business_issue'                            => 'required|string|max:1250',
                'learning_response_strategy'                => 'required|string|max:2500',
                'short_term_learning_response'              => 'required|string|max:1250',
                'medium_term_learning_response'             => 'required|string|max:1250',
                'long_term_learning_response'               => 'required|string|max:1250',
                'school_priorities'                         => 'required|array',
                'is_required_training'                      => 'required|boolean',
                'expected_annual_participant_number'        => 'required|integer|between:1,500000',
                'communities'                               => 'required|array',
                'departmental_results_framework_indicators' => 'required|array',
                'cost_centre'                               => 'required|string|max:6|regex:/[A-Z][0-9]{5}/',
                'spendings'                                 => 'array',
                'spendings.*.internal_resources'            => 'required|array',
                'spendings.*.cost_description'              => 'required|string|max:250',
                'spendings.*.cost'                          => 'required|integer|between:0,999999',
                'spendings.*.recurrence'                    => 'required|integer',
                'spendings.*.comments'                      => 'nullable|string|max:1250',
                'other_operational_considerations'          => 'nullable|string|max:1250',
                'risks'                                     => 'array',
                'risks.*.risk_type_id'                      => 'required_without:risks.*.risk_type_other|distinct|nullable|integer',
                'risks.*.risk_type_other'                   => 'nullable|string|max:100',
                'risks.*.rationale'                         => 'required|string|max:1250',
                'comments'                                  => 'nullable|string|max:2500',
            ];
        }

        return [];
    }
}
