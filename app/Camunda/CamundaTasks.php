<?php

namespace App\Camunda;

use App\Models\User\User;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;

class CamundaTasks extends CamundaBaseApi
{
    /**
     * Get a specific task from its id.
     *
     * @param  string $id
     * @return object
     */
    public function get(string $id)
    {
        return $this->client->get("task/$id");
    }

    /**
     * Get all tasks.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->client->get('task');
    }

    /**
     * Get tasks assigned to a specific user.
     *
     * @param  User $user
     * @return array
     */
    public function for(User $user)
    {
        return $this->client->get('task', ['assignee' => $user->username]);
    }

    /**
     * Get available tasks for a specific user.
     *
     * @param  User $user
     * @return array
     */
    public function availableFor(User $user)
    {
        return $this->client->get('task', ['candidateUser' => $user->username]);
    }

    /**
     * Claim a specific task from its id.
     *
     * @param  string $taskId
     * @param  User $user
     * @return void
     */
    public function claim(string $id, User $user = null)
    {
        return $this->client->post("task/$id/claim", [
            'id' => $id,
            'userId' => isset($user) ? $user->username : $this->username,
        ]);
    }

    /**
     * Unclaim a task from its id.
     *
     * @param  string $taskId
     * @return void
     */
    public function unclaim(string $id)
    {
        return $this->client->post("task/$id/unclaim");
    }

    /**
     * Assign a task to a specific user.
     *
     * @param  string $id
     * @param  User $user
     * @return void
     */
    public function assign(string $id, User $user = null)
    {
        return $this->client->post("task/$taskId/assignee", [
            'id' => $id,
            'userId' => isset($user) ? $user->username : $this->username,
        ]);
    }
}
