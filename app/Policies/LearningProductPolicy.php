<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\LearningProduct\LearningProduct;
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
        //
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
        // Ensure that project has no running processes.
        if ($learningProduct->currentProcess) {
            throw new OperationDeniedException();
        }

        if ($user->isAdmin()) {
            // @todo: Ensure that project has no child learning products.
            return true;
        }

        // Ensure that user is part of the project's organizational unit.
        if (! $user->belongsToOrganizationalUnit($learningProduct->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that project is still new.
        if (! $learningProduct->state->name_key === 'new') {
            throw new OperationDeniedException();
        }

        return true;
    }
}
