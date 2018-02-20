<?php

namespace App\Repositories;

use App\Models\User\User;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseEloquentRepository
{
    protected $model = \App\Models\User\User::class;
    protected $relationships = ['organizationUnits', 'projects'];
    protected $requiredRelationships = ['organizationUnits'];

    public function searchLdap($search)
    {
        return Adldap::search()
            ->setDn('OU=Users,OU=NCR,OU=CSPS,DC=csps-efpc,DC=com')
            ->where('cn', 'contains', $search)
            ->limit(5)
            ->get()->values();
    }

    public function getCurrent()
    {
        $user = Auth::user();
        return $user ? $this->getById($user->id) : null;
    }

    public function delete($id)
    {
        $delete = parent::delete($id);
        return $delete;
    }
}
