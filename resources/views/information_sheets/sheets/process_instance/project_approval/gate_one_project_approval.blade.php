@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['entity']]) @endproject_details

    {{-- Render all process forms components --}}
    @php $forms = collect($data['forms'])->keyBy('entity_type'); @endphp
    @business_case(['data' => $forms['business-case']]) @endbusiness_case
    @planned_product_list(['data' => $forms['planned-product-list']]) @endplanned_product_list
    @gate_one_approval(['data' => $forms['gate-one-approval']]) @endgate_one_approval

@endsection
