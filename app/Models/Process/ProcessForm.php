<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class ProcessForm extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'name_key',
        'name_en',
        'name_fr',
        'process_step_id',
        'display_sequence',
    ];

    protected $localizable = [
        'name',
    ];

    public $timestamps = false;

    public function step()
    {
        return $this->belongsTo(ProcessStep::class, 'process_step_id');
    }

    public function assessments()
    {
        return $this->hasMany(ProcessFormAssessment::class, 'process_form_id');
    }
}
