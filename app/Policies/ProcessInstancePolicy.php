<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
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
            throw new InsufficientPrivilegesException();
        }

        // Ensure process instance state is running.
        if ($processInstance->state->name_key !== 'running') {
            throw new OperationDeniedException();
        }

        return true;
    }
}
