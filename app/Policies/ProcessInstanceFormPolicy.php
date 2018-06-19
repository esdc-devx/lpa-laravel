<?php

namespace App\Policies;

use App\Models\Process\ProcessInstanceForm;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProcessInstanceFormPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can claim the form to edit it.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $model
     * @return boolean
     */
    public function claim(User $user, ProcessInstanceForm $processInstanceForm)
    {
        $processInstanceForm->load(['currentEditor', 'state']);

        // Ensure that form is not already claimed by someone else.
        if (! is_null($processInstanceForm->currentEditor)) {
            return false;
        }

        // Ensure that user have the right roles.
        if (! $user->isAdmin() && ! $user->hasRole('process-contributor')) {
            return false;
        }

        // Ensure that user is part of candidate editor organizational unit.
        if (! $user->organizationalUnits->firstWhere('id', $processInstanceForm->organizational_unit_id)) {
            return false;
        }

        // Ensure that current form state is valid.
        if (! in_array($processInstanceForm->state->name_key, ['unlocked', 'rejected'])) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can unclaim the form.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $model
     * @return boolean
     */
    public function unclaim(User $user, ProcessInstanceForm $processInstanceForm)
    {
        $processInstanceForm->load('currentEditor');

        // Ensure current editor is the same as the user making the request.
        if (! is_null($processInstanceForm->currentEditor) && $processInstanceForm->currentEditor->is($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can edit the form.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $model
     * @return boolean
     */
    public function edit(User $user, ProcessInstanceForm $processInstanceForm)
    {
        // Ensure current editor is the same as the user making the request.
        if (! is_null($processInstanceForm->currentEditor) && $processInstanceForm->currentEditor->is($user)) {
            return true;
        }

        return false;
    }
}
