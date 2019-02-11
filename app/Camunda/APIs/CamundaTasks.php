<?php

namespace App\Camunda\APIs;

use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;
use App\Models\Process\ProcessInstance;
use App\Models\User\User;

class CamundaTasks extends CamundaBaseAPI
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
     * Get tasks for a user or process instance.
     *
     * @param  mixed $source
     * @return array
     */
    public function for($source)
    {
        if ($source instanceof User) {
            return $this->client->get('task', ['assignee' => $user->username]);
        }
        if ($source instanceof ProcessInstance) {
            return $this->client->get('task', ['processInstanceId' => $source->engine_process_instance_id]);
        }
        return [];
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
        return $this->client->post("task/$id/assignee", [
            'id' => $id,
            'userId' => isset($user) ? $user->username : $this->username,
        ]);
    }

    /**
     * Get task candidate groups and users.
     *
     * @param  string $id
     * @return void
     */
    public function getCandidates(string $id)
    {
        return $this->client->get("task/$id/identity-links");
    }

    /**
     * Add a candidate user or group for a given task.
     *
     * @param  string $id
     * @param  array $candidate
     * @return void
     */
    public function addCandidate(string $id, array $candidate)
    {
        $candidate['type'] = $candidate['type'] ?? 'candidate';
        return $this->client->post("task/$id/identity-links", $candidate);
    }

    /**
     * Remove a candidate user or group for a given task.
     *
     * @param  string $id
     * @param  array $candidate
     * @return void
     */
    public function removeCandidate(string $id, array $candidate)
    {
        $candidate['type'] = $candidate['type'] ?? 'candidate';
        return $this->client->post("task/$id/identity-links/delete", $candidate);
    }

    /**
     * Complete task.
     *
     * @param  string $id
     * @param  array $variables
     * @return void
     */
    public function complete(string $id, array $variables = null)
    {
        return $this->client->post("task/$id/complete", [
            'variables' => $variables
        ]);
    }
}
