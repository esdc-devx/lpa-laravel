<?php

namespace App\Models;

use App\Models\ListableModel;
use App\Models\User\User;

class OrganizationalUnit extends ListableModel
{
    protected $casts = [
        'owner' => 'boolean',
    ];

    protected $fillable = [
        'parent_id',
        'name_key',
        'name_en',
        'name_fr',
        'active',
        'owner',
        'email',
        'director',
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

    /**
     * Get all learning product owners organizational units
     * for a specific user.
     *
     * @param  App\Models\User\User $user
     * @return Illuminate\Support\Collection
     */
    public static function getLearningProductOwnersFor($user)
    {
        // If user is admin, return all available choices.
        if ($user->isAdmin()) {
            return static::learningProductOwners()->get();
        }

        // Return organizational units of type owner for user.
        return $user->organizationalUnits->filter(function ($organizationalUnit) {
            return $organizationalUnit->owner == true;
        });
    }
}
