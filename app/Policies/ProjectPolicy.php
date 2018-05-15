<?php

namespace App\Policies;

use App\Models\User\User;
use App\Models\Project\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Helper function which validates that user is part of project's organizational unit.
     *
     * @param  User $user
     * @param  Project $project
     * @return boolean
     */
    protected function userOwnProject(User $user, Project $project)
    {
        return $user->organizationalUnits->firstWhere('id', $project->organizationalUnit->id) !== null;
    }

    /**
     * Get executed before each action.
     *
     * @param  User $user | Current user.
     * @param  string $ability | Current action (update, create, delete, etc.).
     * @return void
     */
    public function before($user, $ability)
    {
        // For admin users, allow any actions except for delete which has an extra validation.
        if ($user->isAdmin() && $ability !== 'delete') {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user) {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Project $model
     * @return mixed
     */
    public function update(User $user, Project $project) {
        if (!$user->hasRole('owner')) {
            return false;
        }
        // Ensure that  user is part of the project's organizational unit.
        return $this->userOwnProject($user, $project);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project) {
        if ($user->isAdmin()) {
            // @todo: Ensure that project has no child learning products.
            return true;
        }

        if (!$user->hasRole('owner')) {
            return false;
        }

        // @todo: Ensure that project has no running processes.


        // Ensure that  user is part of the project's organizational unit and that project is still new.
        if ($this->userOwnProject($user, $project) && $project->state->name_key === 'new') {
            return true;
        }

        return false;
    }
}
