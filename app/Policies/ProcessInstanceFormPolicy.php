<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
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
            throw new OperationDeniedException(trans('errors.process_instance_form.already_claimed'));
        }

        // Admin users can claim the form from this point.
        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that process instance state is running.
        if ($processInstanceForm->step->processInstance->state->name_key !== 'running') {
            throw new OperationDeniedException(trans('errors.process_instance_form.invalid_process_state'));
        }

        // Ensure that current form state is valid.
        if (! in_array($processInstanceForm->state->name_key, ['unlocked', 'rejected'])) {
            throw new OperationDeniedException(trans('errors.process_instance_form.invalid_state'));
        }

        // Ensure that user have the right roles.
        if (! $user->hasRole('process-contributor')) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that user is part of candidate editor organizational unit.
        if (! $user->belongsToOrganizationalUnit($processInstanceForm->organizational_unit_id)) {
            throw new InsufficientPrivilegesException(trans('errors.process_instance_form.invalid_organizational_unit'));
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

        throw new OperationDeniedException(trans('errors.process_instance_form.unclaim'));
    }

    /**
     * Determine whether the user can release the form.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $model
     * @param  User $editor
     * @return boolean
     */
    public function release(User $user, ProcessInstanceForm $processInstanceForm, $editor)
    {
        // Ensure user has the right role.
        if (! $user->isAdmin()) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure form still has a current editor.
        if (is_null($processInstanceForm->currentEditor)) {
            throw new OperationDeniedException();
        }

        // Ensure form editor is not current user.
        if ($processInstanceForm->currentEditor->is($user)) {
            throw new OperationDeniedException();
        }

        // Ensure current form editor is still the same one as it was after making the request.
        if ($editor) {
            if (! $processInstanceForm->currentEditor->is($editor)) {
                throw new OperationDeniedException();
            }
        }

        return true;
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
        $processInstanceForm->load('currentEditor');

        // Ensure the form is claimed.
        if (is_null($processInstanceForm->currentEditor)) {
            throw new OperationDeniedException();
        }

        // Ensure current editor is the same as the user making the request.
        if (! $processInstanceForm->currentEditor->is($user)) {
            throw new OperationDeniedException();
        }

        // Admin users can edit the form from this point.
        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that user have the right roles.
        if (! $user->hasRole('process-contributor')) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that user is part of candidate editor organizational unit.
        if (! $user->belongsToOrganizationalUnit($processInstanceForm->organizational_unit_id)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that process instance state is running.
        if ($processInstanceForm->step->processInstance->state->name_key !== 'running') {
            throw new OperationDeniedException();
        }

        return true;
    }

    /**
     * Determine whether the user can submit the form.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $model
     * @return boolean
     */
    public function submit(User $user, ProcessInstanceForm $processInstanceForm)
    {
        // Ensure that current form state is valid.
        if (! in_array($processInstanceForm->state->name_key, ['unlocked', 'rejected'])) {
            throw new OperationDeniedException();
        }

        $processInstanceForm->load('currentEditor');

        // Ensure the form is claimed.
        if (is_null($processInstanceForm->currentEditor)) {
            throw new OperationDeniedException();
        }

        // Ensure current editor is the same as the user making the request.
        if (! $processInstanceForm->currentEditor->is($user)) {
            throw new OperationDeniedException();
        }

        // Ensure that process instance state is running.
        if ($processInstanceForm->step->processInstance->state->name_key !== 'running') {
            throw new OperationDeniedException();
        }

        // Admin users can edit the form from this point.
        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that user have the right roles.
        if (! $user->hasRole('process-contributor')) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that user is part of candidate editor organizational unit.
        if (! $user->belongsToOrganizationalUnit($processInstanceForm->organizational_unit_id)) {
            throw new InsufficientPrivilegesException();
        }

        return true;
    }
}
