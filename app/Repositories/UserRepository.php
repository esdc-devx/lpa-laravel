<?php

namespace App\Repositories;

use App\Events\UserSaved;
use App\Events\UserCreated;
use App\Models\User\User;
use App\Http\Resources\UserLdap;
use Adldap\Laravel\Facades\Adldap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseEloquentRepository
{
    const LDAP_SEARCH_LIMIT = 5;

    protected $model = \App\Models\User\User::class;
    protected $relationships = ['organizationUnits', 'projects'];
    protected $requiredRelationships = ['organizationUnits'];

    public function searchLdap($search, $limit = self::LDAP_SEARCH_LIMIT)
    {
        return Adldap::search()
            ->setDn('OU=Users,OU=NCR,OU=CSPS,DC=csps-efpc,DC=com')
            ->whereHas('samaccountname')
            ->whereHas('mail')
            ->whereHas('givenname')
            ->whereHas('sn')
            ->where('cn', 'contains', $search)
            ->sortBy('cn', 'asc')
            ->limit($limit)
            ->get()
            ->values();
    }

    public function getUserFromLdap($username)
    {
        // @todo: Move logic to a more generic search ldap method.
        return Adldap::search()
            ->setDn('OU=Users,OU=NCR,OU=CSPS,DC=csps-efpc,DC=com')
            ->where(['samaccountname' => $username])
            ->get();
    }

    public function getCurrent()
    {
        $user = Auth::user();
        return $user ? $this->getById($user->id) : null;
    }

    public function create(array $data)
    {
        // Wrap the creation process within a database transaction
        // to ensure easy rollback in case something goes wrong.
        DB::beginTransaction();

        // Fetch user information from active directory.
        $ldapUserInfo = (new UserLdap($this->getUserFromLdap($data['username'])->first()))
            ->toArray();

        // Since we authenticate users using LDAP, we can just store a random password.
        $password = str_random(16);

        try {
            // Create user with its relationships.
            $user = $this->model->create(
                array_merge($ldapUserInfo, ['password' => $password])
            );
            if (isset($data['organization_units'])) {
                $user->organizationUnits()->attach($data['organization_units']);
            }
        }
        // Rollback transaction if any exceptions occurs.
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        // Dispatch UserCreated event.
        event(new UserCreated($user));

        return $this->getById($user->id);
    }

    public function update($id, array $data)
    {
        // Update organizational units.
        if (isset($data['organization_units'])) {
            $this->getById($id)
                ->organizationUnits()
                ->sync($data['organization_units']);
        }
        $user = $this->getById($id);

        // Dispatch UserSaved event.
        event(new UserSaved($user));

        return $user;
    }

    public function delete($id)
    {
        $delete = parent::delete($id);
        return $delete;
    }
}
