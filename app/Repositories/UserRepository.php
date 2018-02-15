<?php

namespace App\Repositories;

use App\Models\User\User;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function searchLdap($search)
    {
        $provider = Adldap::connect();
        return $provider->search()
            ->users()
            ->where('cn', 'contains', $search)
            ->get();
    }

    public function getCurrent()
    {
        $user = Auth::user();
        return $user ? $this->getById($user->id) : null;
    }

    public function getAll($limit = 0)
    {
        return $limit ? User::paginate($limit) : User::all();
    }

    public function getById($id)
    {
        return User::with(['organizationUnits'])->findOrfail($id);
    }
}
