<dt>{{ $label ?? trans("forms/$field.label") }}</dt>
<dd>{{ isset($data) ? \Carbon\Carbon::parse($data)->toDateString() : trans('entities/general.none') }}</dd>
