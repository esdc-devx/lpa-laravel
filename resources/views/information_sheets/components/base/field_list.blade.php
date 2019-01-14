<dt>{{ $label ?? trans("forms/$field.label") }}</dt>
<dd>
@forelse ($data as $item)
    @if ($loop->first)
    <ul>
    @endif
        <li>{{ $item['name'] }}</li>
    @if ($loop->last)
    </ul>
    @endif
@empty
    {{ trans('entities/general.none') }}
@endforelse
</dd>
