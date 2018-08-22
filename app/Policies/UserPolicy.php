<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Get executed before each action.
     *
     * @param  User $user | Current user.
     * @param  string $ability | Current action (update, create, delete, etc.).
     * @return void
     */
    public function before($user, $ability)
    {
        // Deny any action if users is not admin.
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }
    }

    /**
     * Determine whether the user can search models.
     *
     * @param  User $user
     * @return void
     */
    public function search(User $user) {
        return true;
    }

    /**
     * Determine whether the user can view models.
     *
     * @param  User $user
     * @return mixed
     */
    public function view(User $user, User $model) {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user) {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  User $model
     * @return mixed
     */
    public function update(User $user, User $model) {
        // Prevent any update operations on system admin account.
        if (strcasecmp($model->username, config('auth.admin.username')) === 0) {
            throw new OperationDeniedException();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @return mixed
     */
    public function delete(User $user) {
        return true;
    }
}
