<dt>{{ $label ?? trans("forms/$field.label") }}</dt>
<dd>{!! isset($data) ? nl2br($data) : trans('entities/general.none') !!}</dd>
