@cardbox(['title' => trans_choice('entities/learning_product.label', 2)])
    @forelse($data as $learningProduct)
        @field_group(['title' => trans_choice('entities/learning_product.label', 1), 'index' => $loop->iteration])
            @php $learningProductType = $learningProduct['type']['name'] . ($learningProduct['sub_type'] ? ' / ' . $learningProduct['sub_type']['name'] : ''); @endphp
            <dl>
                @field_lpa_number(['data' => $learningProduct['id']]) @endfield_lpa_number
                @field(['data' => $learningProduct['name'], 'label' => trans('entities/general.name')]) @endfield
                @field(['data' => $learningProductType, 'label' => trans('entities/learning_product.type')]) @endfield
                @field(['data' => $learningProduct['state']['name'], 'label' => trans('entities/general.status')]) @endfield
                @field(['data' => $learningProduct['current_process']['definition']['name'], 'label' => trans('entities/process.current')]) @endfield
                @field(['data' => $learningProduct['organizational_unit']['name'], 'label' => trans_choice('entities/general.organizational_units', 1)]) @endfield
                @field(['data' => $learningProduct['manager']['name'], 'label' => trans('entities/learning_product.manager')]) @endfield
                @field(['data' => $learningProduct['primary_contact']['name'], 'label' => trans('entities/learning_product.primary_contact')]) @endfield
                @field_audit(['data' => $learningProduct, 'type' => 'created']) @endfield_audit
                @field_audit(['data' => $learningProduct, 'type' => 'updated']) @endfield_audit
            </dl>
        @endfield_group
    @empty
        {{ trans('entities/general.none') }}
    @endforelse
@endcardbox
