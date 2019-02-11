@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Project Objective --}}
    @form_section(['title' => trans('forms/business_case.tabs.project_objective')])
        <dl>
            @field_list(['data' => $formData['request_origins'], 'field' => 'business_case.request_origins']) @endfield_list
            @field(['data' => $formData['request_origins_other'], 'field' => 'business_case.request_origins_other']) @endfield
            @field(['data' => $formData['business_issue'], 'field' => 'business_case.business_issue']) @endfield
        </dl>
    @endform_section

    {{-- Proposed Solution --}}
    @form_section(['title' => trans('forms/business_case.tabs.proposed_solution')])
        <dl>
            @field(['data' => $formData['short_term_learning_response'], 'field' => 'business_case.short_term_learning_response']) @endfield
            @field(['data' => $formData['medium_term_learning_response'], 'field' => 'business_case.medium_term_learning_response']) @endfield
            @field(['data' => $formData['long_term_learning_response'], 'field' => 'business_case.long_term_learning_response']) @endfield
        </dl>
    @endform_section

    {{-- School Priorities --}}
    @form_section(['title' => trans('forms/business_case.tabs.school_priorities')])
        <dl>
            @field_list(['data' => $formData['school_priorities'], 'field' => 'business_case.school_priorities']) @endfield_list
            @field_boolean(['data' => $formData['is_required_training'], 'field' => 'business_case.is_required_training']) @endfield_boolean
        </dl>
    @endform_section

    {{-- Target Audience --}}
    @form_section(['title' => trans('forms/business_case.tabs.target_audience')])
        <dl>
            @field(['data' => $formData['expected_annual_participant_number'], 'field' => 'business_case.expected_annual_participant_number']) @endfield
            @field_list(['data' => $formData['communities'], 'field' => 'business_case.communities']) @endfield_list
        </dl>
    @endform_section

    {{-- Departmental Results Framework --}}
    @form_section(['title' => trans('forms/business_case.tabs.departmental_results_framework')])
        <dl>
            @field_list(['data' => $formData['departmental_results_framework_indicators'], 'field' => 'business_case.departmental_results_framework_indicators']) @endfield_list
        </dl>
    @endform_section

    {{-- Costs and Resources --}}
    @form_section(['title' => trans('forms/business_case.tabs.costs')])
        <dl>
            @field(['data' => $formData['cost_centre'], 'field' => 'business_case.cost_centre']) @endfield
        </dl>
        {{-- Cost Breakdown --}}
        <h4>{{ trans_choice('forms/business_case.form_section_groups.spending', 2) }}</h4>
        @forelse($formData['spendings'] as $spending)
            @field_group(['field' => 'business_case.form_section_groups.spending', 'index' => $loop->iteration])
                <dl>
                    @field(['data' => $spending['internal_resource']['name'], 'field' => 'business_case.spendings.internal_resource']) @endfield
                    @field(['data' => $spending['cost_description'], 'field' => 'business_case.spendings.cost_description']) @endfield
                    @field(['data' => $spending['cost'], 'field' => 'business_case.spendings.cost']) @endfield
                    @field(['data' => $spending['recurrence']['name'] , 'field' => 'business_case.spendings.recurrence']) @endfield
                    @field(['data' => $spending['comments'], 'field' => 'business_case.spendings.comments']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse
        <dl>
            @field(['data' => $formData['other_operational_considerations'], 'field' => 'business_case.other_operational_considerations']) @endfield
        </dl>
    @endform_section

    {{-- Risks --}}
    @form_section(['title' => trans('forms/business_case.tabs.risk')])
        <h4>{{ trans_choice('forms/business_case.tabs.risk', 2) }}</h4>
        @forelse($formData['risks'] as $risk)
            @field_group(['field' => 'business_case.form_section_groups.risk', 'index' => $loop->iteration])
                <dl>
                    @field(['data' => $risk['risk_type']['name'], 'field' => 'business_case.risks.risk_type']) @endfield
                    @field(['data' => $risk['risk_type_other'], 'field' => 'business_case.risks.risk_type_other']) @endfield
                    @field(['data' => $risk['rationale'], 'field' => 'business_case.risks.risk_rationale']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/business_case.tabs.comments')])
        <dl>
            @field(['data' => $formData['comments'], 'field' => 'business_case.comments']) @endfield
        </dl>
    @endform_section

@endform
