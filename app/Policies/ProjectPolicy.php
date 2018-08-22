<?php

namespace App\Policies;

use App\Exceptions\InsufficientPrivilegesException;
use App\Exceptions\OperationDeniedException;
use App\Models\User\User;
use App\Models\Process\ProcessDefinition;
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
        if (! $user->hasRole('owner') || ! $this->userOwnProject($user, $project)) {
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
            // @todo: Ensure that project has no child learning products.
            return true;
        }

        // Ensure that user is part of the project's organizational unit.
        if (! $this->userOwnProject($user, $project)) {
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
        if (! $user->isAdmin() && ! $this->userOwnProject($user, $project)) {
            throw new InsufficientPrivilegesException();
        }

        // Ensure that project has no running processes.
        if ($project->currentProcess) {
            throw new OperationDeniedException();
        }

        // Handle any business logic for each process.
        switch ($processDefinition->name_key) {
            case 'project-approval':
                // Ensure project is new.
                if ($project->state->name_key !== 'new') {
                    throw new OperationDeniedException();
                }
        }

        return true;
    }
}
