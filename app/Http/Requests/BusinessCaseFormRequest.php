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
                'request_sources' => 'required_without:request_source_other|array',
                'request_source_other' => 'nullable|string|max:100',
                'business_issue' => 'required|string|max:1250',
                'learning_response_strategy' => 'required|string|max:2500',
                'potential_solution_types' => 'required_without:potential_solution_type_other|array',
                'potential_solution_type_other' => 'nullable|string|max:1250',
                'is_required_training' => 'required|boolean',
            ];
        }

        return [];
    }
}
