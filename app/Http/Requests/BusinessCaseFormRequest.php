<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessCaseFormRequest extends FormRequest
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
                'request_sources'                                         => 'required_without:request_source_other|array',
                'request_source_other'                                    => 'nullable|string|max:100',
                'business_issue'                                          => 'required|string|max:1250',
                'learning_response_strategy'                              => 'required|string|max:2500',
                'potential_solution_types'                                => 'required_without:potential_solution_type_other|array',
                'potential_solution_type_other'                           => 'nullable|string|max:1250',
                'is_required_training'                                    => 'required|boolean',
                'timeframe_id'                                            => 'required|integer',
                'timeframe_rationale'                                     => 'required|string|max:1250',
                'communities'                                             => 'required|array',
                'expected_annual_participant_number'                      => 'required|integer|between:1,500000',
                'departmental_benefits'                                   => 'required|array|min:1',
                'departmental_benefits.*.departmental_benefit_type_id'    => 'required_without:departmental_benefits.*.departmental_benefit_type_other|integer',
                'departmental_benefits.*.departmental_benefit_type_other' => 'nullable|string|max:100',
                'departmental_benefits.*.rationale'                       => 'required|string|max:1250',
                'learners_benefits'                                       => 'required|array|min:1',
                'learners_benefits.*.learners_benefit_type_id'            => 'required_without:learners_benefits.*.learners_benefit_type_other|integer',
                'learners_benefits.*.learners_benefit_type_other'         => 'nullable|string|max:100',
                'learners_benefits.*.rationale'                           => 'required|string|max:1250',
                'cost_center'                                             => 'required|string|max:6|regex:/[A-Z][0-9]{5}/',
                'maintenance_fund_id'                                     => 'required|integer',
                'maintenance_fund_rationale'                              => 'required_unless:maintenance_fund_id,1|string|nullable|max:1250',
                'salary_fund_id'                                          => 'required|integer',
                'salary_fund_rationale'                                   => 'required_unless:salary_fund_id,1|string|nullable|max:1250',
                'internal_resources'                                      => 'required_without:internal_resource_other|array',
                'internal_resource_other'                                 => 'nullable|string|max:1250',
                'risks'                                                   => 'required|array|min:1',
                'risks.*.risk_type_id'                                    => 'required_without:risks.*.risk_type_other|integer',
                'risks.*.risk_type_other'                                 => 'nullable|string|max:100',
                'risks.*.risk_impact_level_id'                            => 'required|integer',
                'risks.*.risk_probability_level_id'                       => 'required|integer',
                'risks.*.rationale'                                       => 'required|string|max:1250',
                'comment'                                                 => 'nullable|string|max:2500',
            ];
        }

        return [];
    }
}
