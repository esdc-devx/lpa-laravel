<?php

namespace App\Models;

use App\Models\ListableModel;
use App\Models\Traits\UsesKeyNameField;
use App\Models\User\User;

class OrganizationalUnit extends ListableModel
{
    protected $casts = [
        'owner' => 'boolean',
    ];

    public $timestamps = true;

    public function director()
    {
        return $this->belongsTo(User::class, 'director');
    }

    public function scopeLearningProductOwners($query)
    {
        $query->where('owner', true);
    }
}
