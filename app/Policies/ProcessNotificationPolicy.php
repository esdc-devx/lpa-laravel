<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Models\Process\ProcessNotification;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessNotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view models.
     *
     * @param  User $user
     * @param  ProcessNotification $model
     * @return boolean
     */
    public function view(User $user, ProcessNotification $model) {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return boolean
     */
    public function create(User $user) {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  ProcessNotification $model
     * @return boolean
     */
    public function update(User $user, ProcessNotification $model) {
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  ProcessNotification $model
     * @return boolean
     */
    public function delete(User $user, ProcessNotification $model) {
        return false;
    }
}
