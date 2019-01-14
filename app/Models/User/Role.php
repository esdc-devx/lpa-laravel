<?php

namespace App\Models\User;

use App\Models\ListableModel;

class Role extends ListableModel
{
    protected $touches = [
        'users',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
