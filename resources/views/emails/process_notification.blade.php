@component('mail::layout')

{{-- Header --}}

@slot('header')
    @component('mail::header', ['url' => ''])
    {{ $entity->name }}
    @endcomponent
@endslot

{{-- French version --}}

@slot('bodyFr')
    {{ $content['fr']['body'] }}
@endslot

@slot('actionFr')
    @component('mail::button', ['url' => $content['fr']['actionUrl']])
    {{ $content['fr']['actionText'] }}
    @endcomponent
@endslot

{{-- English version --}}

@slot('bodyEn')
    {{ $content['en']['body'] }}
@endslot

@slot('actionEn')
    @component('mail::button', ['url' => $content['en']['actionUrl']])
    {{ $content['en']['actionText'] }}
    @endcomponent
@endslot

{{-- Footer --}}

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} CSPS / EFPC
    @endcomponent
@endslot

@endcomponent
