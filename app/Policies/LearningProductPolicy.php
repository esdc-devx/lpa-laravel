<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\LearningProduct\LearningProduct;
use App\Models\Process\ProcessDefinition;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningProductPolicy
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
        // Prevent any actions if user is not an admin or doesn't have the owner role.
        if (! $user->isAdmin() && ! $user->hasRole('owner')) {
            throw new InsufficientPrivilegesException();
        }
    }

    /**
     * Determine whether the user can create learning products.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the learning product.
     *
     * @param  User $user
     * @param  LearningProduct $learningProduct
     * @return bool
     */
    public function update(User $user, LearningProduct $learningProduct)
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that user has the right role and is part of the learning product's organizational unit.
        if (! $user->hasRole('owner') || ! $user->belongsToOrganizationalUnit($learningProduct->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the learning product.
     *
     * @param  User $user
     * @param  LearningProduct $learningProduct
     * @return bool
     */
    public function delete(User $user, LearningProduct $learningProduct)
    {
        // Ensure that learning product has no running processes.
        if ($learningProduct->currentProcess) {
            throw new OperationDeniedException();
        }

        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that user is part of the learning product's organizational unit.
        if (! $user->belongsToOrganizationalUnit($learningProduct->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that learning product is still new.
        if (! $learningProduct->state->name_key === 'new') {
            throw new OperationDeniedException();
        }

        return true;
    }

    /**
     * Determine whether the user can start a learning product process.
     *
     * @param  User $user
     * @param  LearningProduct $learningProduct
     * @param  ProcessDefinition $processDefinition
     * @return bool
     */
    public function startProcess(User $user, LearningProduct $learningProduct, ProcessDefinition $processDefinition)
    {
        // Ensure that user is part of the learning product's organizational unit.
        if (! $user->isAdmin() && ! $user->belongsToOrganizationalUnit($learningProduct->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that learning product has no running processes.
        if ($learningProduct->currentProcess) {
            throw new OperationDeniedException();
        }

        // Defer to process definition authorization rules.
        if (! $processDefinition->authorize($learningProduct)) {
            throw new OperationDeniedException();
        }

        return true;
    }
}
