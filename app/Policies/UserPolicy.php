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
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }

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
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }

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
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }

        return true;
    }
}
