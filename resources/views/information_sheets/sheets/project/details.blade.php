@extends('information_sheets.master')

@section('title', $definition['name'])

@section('content')

    {{-- Render Project Details component --}}
    @project_details(['data' => $data['project']]) @endproject_details

    {{-- Render all process forms components --}}
    @business_case(['data' => $data['business_case']]) @endbusiness_case
    @planned_product_list(['data' => $data['planned_product_list']]) @endplanned_product_list

    {{-- Render all learning products components --}}
    @learning_products(['data' => $data['project']['learning_products']]) @endlearning_products

@endsection
