<?php

namespace App\Repositories;

use App\Models\OrganizationalUnit\OrganizationalUnit;

class OrganizationalUnitRepository
{
    public function getAll()
    {
        return OrganizationalUnit::all();
    }

    public function getOwners()
    {
        return OrganizationalUnit::where('owner', true)->get();
    }
}
