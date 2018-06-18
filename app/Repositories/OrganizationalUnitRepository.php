<?php

namespace App\Repositories;

use App\Models\OrganizationalUnit;
use App\Models\User\User;

class OrganizationalUnitRepository extends BaseEloquentRepository
{
    protected $model = OrganizationalUnit::class;
    protected $relationships = ['director'];

    public function getOwners()
    {
        return $this->model->where('owner', true)->get();
    }

    public function getOwnersFor(User $user)
    {
        // If user is admin, return all available choices.
        if ($user->isAdmin()) {
            return $this->getOwners();
        }

        // Return organizational units of type owner for user.
        return $user->organizationalUnits->filter(function ($organizationalUnit) {
            return $organizationalUnit->owner == true;
        });
    }
}
