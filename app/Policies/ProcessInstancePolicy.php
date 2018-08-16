<?php

namespace App\Policies;

use App\Models\Process\ProcessInstance;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessInstancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can cancel the processInstance.
     *
     * @param  User $user
     * @param  ProcessInstance $processInstance
     * @return boolean
     */
    public function cancel(User $user, ProcessInstance $processInstance)
    {
        // Ensure user has admin role.
        if (! $user->isAdmin()) {
            return false;
        }

        // Ensure process instance state is active.
        if ($processInstance->state->name_key !== 'active') {
            return false;
        }

        return true;
    }
}
