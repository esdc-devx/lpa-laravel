<?php

namespace App\Models;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class State extends LocalizableModel
{
    use UsesKeyNameField;

    protected $hidden = ['id', 'entity_type'];
    protected $localizable = ['name'];

    public $timestamps = false;
}
