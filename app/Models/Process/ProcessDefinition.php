<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class ProcessDefinition extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'entity_type',
        'name_key',
        'name_en',
        'name_fr',
    ];

    protected $localizable = [
        'name',
    ];

    public $timestamps = false;

    public function steps()
    {
        return $this->hasMany(ProcessStep::class);
    }
}
