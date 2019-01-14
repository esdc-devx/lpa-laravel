@form(['form' => $data])

    @php $formData = $data['form_data']; @endphp

    {{-- Planned Products --}}
    @form_section(['title' => trans_choice('forms/planned_product_list.tabs.planned_product', 2)])
        <h4>{{ trans_choice('forms/planned_product_list.form_section_groups.planned_product', 2) }}</h4>
        @forelse($formData['planned_products'] as $product)
            @field_group(['title' => 'planned_product_list.form_section_groups.planned_product', 'index' => $loop->iteration])
                <dl>
                    @php $type = isset($product['type']['name']) ? $product['type']['name'] . ' / ' . $product['sub_type']['name'] : null @endphp
                    @field(['data' => $type, 'field' => 'planned_product_list.type']) @endfield
                    @field(['data' => $product['quantity'], 'field' => 'planned_product_list.quantity']) @endfield
                </dl>
            @endfield_group
        @empty
            {{ trans('entities/general.none') }}
        @endforelse
    @endform_section

    {{-- Comments --}}
    @form_section(['title' => trans('forms/planned_product_list.tabs.comments')])
        @field(['data' => $formData['comments'], 'field' => 'planned_product_list.comments']) @endfield
    @endform_section

@endform
