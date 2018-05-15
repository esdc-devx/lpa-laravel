<?php

namespace App\Models\Process;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class ProcessDefinition extends LocalizableModel
{
    use UsesKeyNameField;

    protected $guarded = [];
    protected $hidden = ['id'];
    protected $localizable = ['name'];

    public $timestamps = false;

    public function steps()
    {
        return $this->hasMany(ProcessStep::class);
    }
}
