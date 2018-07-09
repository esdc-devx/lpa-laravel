<?php

namespace App\Models;

use App\Models\LocalizableModel;
use App\Models\Traits\UsesKeyNameField;
use App\Models\User\User;

class OrganizationalUnit extends LocalizableModel
{
    use UsesKeyNameField;

    protected $hidden = ['created_at', 'updated_at'];
    protected $localizable = ['name'];
    protected $casts = [
        'owner' => 'boolean',
    ];

    public function director()
    {
        return $this->belongsTo(User::class, 'director');
    }
}
