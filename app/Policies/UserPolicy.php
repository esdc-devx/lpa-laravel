<?php

namespace App\Policies;

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
        // For admin users, allow any actions except for update which has an extra validation.
        if ($user->isAdmin() && $ability !== 'update') {
            return true;
        }
    }

    /**
     * Determine whether the user can search models.
     *
     * @param  User $user
     * @return void
     */
    public function search(User $user) {}

    /**
     * Determine whether the user can view models.
     *
     * @param  User $user
     * @return mixed
     */
    public function view(User $user, User $model) {}

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user) {}

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  User $model
     * @return mixed
     */
    public function update(User $user, User $model) {
        // Prevent any update operations on admin account.
        if (strcasecmp($model->username, config('auth.admin.username')) === 0) {
            return false;
        }
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @return mixed
     */
    public function delete(User $user) {}
}
