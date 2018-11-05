<?php

namespace App\Models\User;

use App\Models\ListableModel;

class Role extends ListableModel
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
