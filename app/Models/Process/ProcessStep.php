<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;

class ProcessStep extends LocalizableModel
{
    protected $fillable = [
        'name_key',
        'name_en',
        'name_fr',
        'process_definition_id',
        'display_sequence',
    ];

    protected $localizable = [
        'name',
    ];

    public $timestamps = false;

    public function definition()
    {
        return $this->belongsTo(ProcessDefinition::class, 'process_definition_id');
    }

    public function forms()
    {
        return $this->hasMany(ProcessForm::class);
    }
}
