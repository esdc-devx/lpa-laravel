@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp
    @php $entityCancelled = $formData['is_entity_cancelled'] ? 'true' : 'false'; @endphp

    {{-- Overall Assessment --}}
    @form_section(['title' => trans('forms/form_assessment.tabs.overall_assessment')])
        <dl>
            @field(['data' => $formData['assessment_date'], 'field' => 'form_assessment.assessment_date']) @endfield
            @field(['data' => trans("forms/gate_one_approval.is_entity_cancelled.options.{$entityCancelled}"), 'field' => trans('gate_one_approval.is_entity_cancelled')]) @endfield
            @field(['data' => $formData['entity_cancellation_rationale'], 'field' => 'gate_one_approval.entity_cancellation_rationale']) @endfield
        </dl>
    @endform_section

    {{-- Forms Assessment --}}
    @form_assessment(['data' => $formData['assessments']]) @endform_assessment

@endform
