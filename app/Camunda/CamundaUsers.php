<?php

namespace App\Camunda;

use App\Models\User\User;
use App\Camunda\Exceptions\ProcessEngineException;
use App\Camunda\Exceptions\GeneralException;

class CamundaUsers extends CamundaBaseApi
{
    /**
     * Get user profile.
     *
     * @param  User $user
     * @return object
     */
    public function get(User $user)
    {
        return $this->client->get("user/$user->username/profile");
    }

    /**
     * Get list of all users.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->client->get('user');
    }

    /**
     * Create user profile.
     *
     * @param  User $user
     * @return void
     */
    public function create(User $user)
    {
        try {
            return $this->client->post('user/create', [
                'profile' => [
                    'id'        => $user->username,
                    'firstName' => $user->first_name,
                    'lastName'  => $user->last_name,
                    'email'     => $user->email
                ],
                'credentials' => [
                    // @todo: Resolve password from Camunda service instead.
                    'password' => crypt($user->username, config('camunda.authentication.salt'))
                ]
            ]);
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("User [$user->username] could not be created due to an internal server error.");
        }
    }

    /**
     * Delete user.
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user)
    {
        return $this->client->delete("user/$user->username");
    }
}
