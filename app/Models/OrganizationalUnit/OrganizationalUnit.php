<?php

namespace App\Models\OrganizationalUnit;

use App\Models\LocalizableModel;
use App\Models\User\User;

class OrganizationalUnit extends LocalizableModel
{
    protected $guarded = [];
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
