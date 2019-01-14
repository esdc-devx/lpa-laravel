<dt>{{ trans("entities/general.{$type}") }}</dt>
<dd>
    @if(isset($data["{$type}_by"]['name']))
        {{ $data["{$type}_at"] }} <br> {{ $data["{$type}_by"]['name'] }}
    @else
        {{ trans('entities/general.none') }}
    @endif
</dd>
