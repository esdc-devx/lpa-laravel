<?php

namespace App\Models\OrganizationUnit;

class OrganizationUnitRepository
{
    public function getOwners()
    {
        return OrganizationUnit::where('owner', true)
            ->get()
            ->toArray();
    }
}
