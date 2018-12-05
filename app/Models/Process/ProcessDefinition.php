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

    public function getRouteKeyName()
    {
        return 'name_key';
    }

    public function steps()
    {
        return $this->hasMany(ProcessStep::class);
    }

    public function forms()
    {
        return $this->hasManyThrough(ProcessForm::class, ProcessStep::class);
    }

    public function notifications()
    {
        return $this->hasMany(ProcessNotification::class);
    }
}
