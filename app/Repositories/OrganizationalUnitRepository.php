<?php

namespace App\Repositories;

use App\Models\OrganizationalUnit\OrganizationalUnit;

class OrganizationalUnitRepository extends BaseEloquentRepository
{
    protected $model = OrganizationalUnit::class;

    public function getOwners()
    {
        return OrganizationalUnit::where('owner', true)->get();
    }
}
