@foreach($data as $assessment)
    @form_section(['title' => $assessment['assessed_process_form_definition']['name']])
        <dl>
            @field(['data' => $assessment['decision']['name'], 'field' => 'form_assessment.process_form_decision_id']) @endfield
            @field(['data' => $assessment['comments'], 'field' => 'form_assessment.assessment_comments']) @endfield
        </dl>
    @endform_section
@endforeach
