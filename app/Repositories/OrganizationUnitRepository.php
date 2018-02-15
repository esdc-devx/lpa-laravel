<?php

namespace App\Repositories;

use App\Models\OrganizationUnit\OrganizationUnit;

class OrganizationUnitRepository
{
    public function getAll()
    {
        return OrganizationUnit::all();
    }

    public function getOwners()
    {
        return OrganizationUnit::where('owner', true)->get();
    }
}
