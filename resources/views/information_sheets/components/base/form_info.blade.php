<div class="form-info">
    <dl>
        @field(['data' => $form['state']['name'], 'label' => trans('entities/general.status')]) @endfield
        @field(['data' => $form['organizational_unit']['name'], 'label' => trans_choice('entities/form.assigned_organizational_units', 1)]) @endfield_audit
        @field_audit(['data' => $form, 'type' => 'updated']) @endfield_audit
    </dl>
</div>
