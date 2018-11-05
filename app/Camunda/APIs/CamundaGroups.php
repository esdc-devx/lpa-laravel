<?php

namespace App\Camunda\APIs;

use App\Models\User\User;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;

class CamundaGroups extends CamundaBaseAPI
{
    /**
     * Get a specific group from its id.
     *
     * @param  string $id
     * @return object
     */
    public function get(string $id)
    {
        return $this->client->get("group/$id");
    }

    /**
     * Get all groups.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->client->get('group');
    }

    /**
     * Get groups for a specific user.
     *
     * @param  User $user
     * @return array
     */
    public function for(User $user)
    {
        return $this->client->get('identity/groups', ['userId' => $user->username])->groups;
    }

    /**
     * Create a new group.
     *
     * @param  array $data
     * @return void
     */
    public function create(array $data)
    {
        try {
            return $this->client->post('group/create', $data);
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("Group [{$data['id']}] could not be created due to an internal server error.");
        }
    }

    /**
     * Update group information.
     *
     * @param  string $id
     * @param  array $data
     * @return void
     */
    public function update(string $id, array $data)
    {
        try {
            return $this->client->put("group/$id", array_merge(['id' => $id], $data));
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("Group [$id] could not be updated due to an internal server error.");
        }
    }

    /**
     * Delete specific group.
     *
     * @param  string $id
     * @return void
     */
    public function delete(string $id)
    {
        return $this->client->delete("group/$id");
    }

    /**
     * Delete all groups.
     *
     * @return void
     */
    public function deleteAll()
    {
        foreach ($groups = $this->getAll() as $group) {
            // Ensure we don't delete the admin group.
            if ($group->id !== $this->camunda->config('app.groups.admin')) {
                $this->delete($group->id);
            }
        }
    }

    /**
     * Add a user to a group.
     *
     * @param  User $user
     * @param  string $id
     * @return void
     */
    public function add(User $user, string $id)
    {
        try {
            $this->client->put("group/$id/members/$user->username");
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("Could not add [{$user->username}] to group [$id].");
        }
    }

    /**
     * Remove a user from a group.
     *
     * @param  User $user
     * @param  string $id
     * @return void
     */
    public function remove(User $user, string $id)
    {
        try {
            $this->client->delete("group/$id/members/$user->username");
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("Could not remove [{$user->username}] to group [$id].");
        }
    }
}
