<?php

namespace App\Models;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class State extends LocalizableModel
{
    use UsesKeyNameField;

    protected $fillable = [
        'entity_type',
        'name_key',
        'name_en',
        'name_fr',
    ];

    protected $localizable = ['name'];

    public $timestamps = false;
}
