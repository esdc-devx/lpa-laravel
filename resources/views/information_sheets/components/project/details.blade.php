@cardbox(['title' => trans('entities/project.details') ])
    <dl>
        @field_lpa_number(['data' => $data['id']]) @endfield_lpa_number
        @field(['data' => $data['name'], 'label' => trans('entities/general.name')]) @endfield
        @field(['data' => $data['outline'], 'label' => trans('entities/project.outline.label')]) @endfield
        @field(['data' => $data['organizational_unit']['name'], 'label' => trans_choice('entities/general.organizational_units', 1)]) @endfield
        @field(['data' => $data['organizational_unit']['director']['name'], 'label' => trans('entities/organizational_unit.director')]) @endfield
        @field(['data' => $data['state']['name'], 'label' => trans('entities/general.status')]) @endfield
        @field_audit(['data' => $data, 'type' => 'created']) @endfield_audit
        @field_audit(['data' => $data, 'type' => 'updated']) @endfield_audit
    </dl>
@endcardbox
