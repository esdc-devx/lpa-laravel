@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp
    @php $entityCancelled = $formData['is_entity_cancelled'] ? 'true' : 'false'; @endphp

    {{-- Overall Assessment --}}
    @form_section(['title' => trans('forms/form_assessment.tabs.overall_assessment')])
        <dl>
            @field_date(['data' => $formData['assessment_date'], 'field' => 'form_assessment.assessment_date']) @endfield_date
            @field(['data' => trans("forms/form_assessment.entity_cancellation.project.is_entity_cancelled.options.{$entityCancelled}"), 'field' => 'form_assessment.entity_cancellation.project.is_entity_cancelled']) @endfield
            @field(['data' => $formData['entity_cancellation_rationale'], 'field' => 'form_assessment.entity_cancellation.project.entity_cancellation_rationale']) @endfield
        </dl>
    @endform_section

    {{-- Forms Assessment --}}
    @form_assessment(['data' => $formData['assessments']]) @endform_assessment

@endform
