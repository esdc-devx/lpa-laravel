<?php

namespace App\Policies;

use App\Models\User\User as User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Get executed before each action.
     *
     * @param App\Models\User\User $user | Current user.
     * @param string $ability  Current action | (update, create, delete, etc.)
     * @return void
     */
    public function before($user, $ability)
    {
        //@note: if ($user->isAdmin()) return true;
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function create(User $user) {}

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User\User  $user
     * @param  \App\Models\User\User  $model
     * @return mixed
     */
    public function update(User $user, User $model) {}

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User\User  $user
     * @return mixed
     */
    public function delete(User $user) {}
}
