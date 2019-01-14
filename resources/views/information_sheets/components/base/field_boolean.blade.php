<dt>{{ $label ?? trans("forms/$field.label") }}</dt>
<dd>
@isset($data)
    @if ($data)
        {{ trans('base/actions.yes') }}
    @else
        {{ trans('base/actions.no') }}
    @endif
@else
    {{ trans('entities/general.none') }}
@endif
</dt>
