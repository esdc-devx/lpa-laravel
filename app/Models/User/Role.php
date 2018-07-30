<?php

namespace App\Models\User;

use App\Models\ListableModel;
use App\Models\Traits\UsesKeyNameField;

class Role extends ListableModel
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
