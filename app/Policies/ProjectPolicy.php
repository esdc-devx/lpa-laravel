<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\Process\ProcessDefinition;
use App\Models\Project\Project;
use App\Models\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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
     * Determine whether the user can create models.
     *
     * @param  User $user
     * @return mixed
     */
    public function create(User $user) {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  User $user
     * @param  Project $model
     * @return mixed
     */
    public function update(User $user, Project $project) {
        if ($user->isAdmin()) {
            return true;
        }

        // Ensure that user has the right role and is part of the project's organizational unit.
        if (! $user->hasRole('owner') || ! $user->belongsToOrganizationalUnit($project->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  User $user
     * @param  Project $project
     * @return mixed
     */
    public function delete(User $user, Project $project) {
        // Ensure that project has no running processes.
        if ($project->currentProcess) {
            throw new OperationDeniedException();
        }

        if ($user->isAdmin()) {
            // Ensure that project has no child learning products.
            if ($project->learningProducts->isNotEmpty()) {
                throw new OperationDeniedException();
            }
            return true;
        }

        // Ensure that user is part of the project's organizational unit.
        if (! $user->belongsToOrganizationalUnit($project->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that project is still new.
        if (! $project->state->name_key === 'new') {
            throw new OperationDeniedException();
        }

        return true;
    }

    /**
     * Determine whether the user can start a project process.
     *
     * @param  User $user
     * @param  Project $project
     * @param  ProcessDefinition $processDefinition
     * @return mixed
     */
    public function startProcess(User $user, Project $project, ProcessDefinition $processDefinition)
    {
        // Ensure that user is part of the project's organizational unit.
        if (! $user->isAdmin() && ! $user->belongsToOrganizationalUnit($project->organizationalUnit)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that project has no running processes.
        if ($project->currentProcess) {
            throw new OperationDeniedException();
        }

        // Defer to process definition authorization rules.
        if (! $processDefinition->authorize($project)) {
            throw new OperationDeniedException();
        }

        return true;
    }
}
