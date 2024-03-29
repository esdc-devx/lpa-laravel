<?php

namespace App\Repositories;

use Adldap;
use Adldap\Models\ModelDoesNotExistException;
use App\Events\UserSaved;
use App\Http\Resources\UserLdap;
use App\Models\User\User;
use DB;

class UserRepository extends BaseEloquentRepository
{
    const LDAP_SEARCH_LIMIT = 5;

    protected $model = User::class;
    protected $relationships = ['roles', 'organizationalUnits', 'createdBy', 'updatedBy'];
    protected $requiredRelationships = ['organizationalUnits'];

    /**
     * Search users in active directory by their name.
     *
     * @param  string $search
     * @param  int $limit
     * @return Illuminate\Support\Collection
     */
    public function searchLdap($search, $limit = self::LDAP_SEARCH_LIMIT)
    {
        return Adldap::search()
            ->setDn('DC=csps-efpc,DC=com')
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

    /**
     * Retrieve user in active directory from its username.
     *
     * @param  string $username
     * @return Adldap\Models\User
     */
    public function getUserFromLdap($username)
    {
        // @todo: Move logic to a more generic search ldap method.
        if ($user = Adldap::search()->where(['samaccountname' => $username])->first()) {
            return $user;
        }
        throw new ModelDoesNotExistException();
    }

    /**
     * Fetch or create user if it doesn't exists.
     *
     * @param  string $username
     * @return User
     */
    public function findOrCreate($username)
    {
        if (! $user = $this->getItemByColumn('username', $username)) {
            $user = $this->create(['username' => $username]);
        }

        return $user;
    }

    /**
     * Return current authenticated user.
     *
     * @return App\Models\User|null
     */
    public function getCurrent()
    {
        $user = auth()->user();
        return $user ? $user->load(['organizationalUnits', 'roles']) : null;
    }

    /**
     * Create user.
     *
     * @param  array $data
     * @return User
     */
    public function create(array $data)
    {
        // Wrap the creation process within a database transaction
        // to ensure easy rollback in case something goes wrong.
        DB::beginTransaction();

        // Fetch user information from active directory.
        $ldapUserInfo = (new UserLdap($this->getUserFromLdap($data['username'])))->toArray();

        // Since we authenticate users using LDAP, we can just store a random password.
        $ldapUserInfo['password'] = $data['password'] ?? bcrypt(str_random(16));
        // When we first install the application, we are not yet authenticated thus we can't populate those values.
        $ldapUserInfo['created_by'] = auth()->user()->id ?? null;
        $ldapUserInfo['updated_by'] = auth()->user()->id ?? null;
        try {
            // Create user with its relationships.
            $user = $this->model->create($ldapUserInfo);
            if (isset($data['organizational_units'])) {
                $user->organizationalUnits()->attach($data['organizational_units']);
            }
            if (isset($data['roles'])) {
                $user->roles()->attach($data['roles']);
            }
        }
        // Rollback transaction if any exceptions occurs.
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        // Return user with all of its relationships.
        return $this->with('all')->getById($user->id);
    }

    /**
     * Update user.
     *
     * @param  array $data
     * @return User
     */
    public function update($id, array $data = [])
    {
        $user = $this->getById($id);

        // Update organizational units.
        if (isset($data['organizational_units'])) {
            $user->organizationalUnits()->sync($data['organizational_units']);
        }

        // Update user roles.
        if (isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        // Update user audit information.
        $user->updateAudit();

        // Dispatch UserSaved event and return its updated value.
        $user = $this->with('roles')->getById($id);
        event(new UserSaved($user));
        return $user;
    }
}
