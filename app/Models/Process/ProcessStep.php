<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;

class ProcessStep extends LocalizableModel
{
    protected $hidden = ['id', 'process_definition_id'];
    protected $localizable = ['name'];

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