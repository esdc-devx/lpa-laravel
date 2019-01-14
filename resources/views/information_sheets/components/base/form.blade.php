@cardbox(['title' => $form['definition']['name'] ])

    {{-- Form meta information --}}
    @form_info(['form' => $form]) @endform_info

    {{-- Form data --}}
    {{ $slot }}

@endcardbox
