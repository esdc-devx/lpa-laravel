<?php

namespace App\Models\User;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;

class Role extends LocalizableModel
{
    use UsesKeyNameField;

    protected $localizable = ['name'];
    protected $hidden = ['pivot'];

    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
