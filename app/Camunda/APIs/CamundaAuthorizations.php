<?php

namespace App\Camunda\APIs;

use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;

class CamundaAuthorizations extends CamundaBaseAPI
{
    // Define Camunda constants for authorization types.
    const AUTH_TYPE_GLOBAL = 0;
    const AUTH_TYPE_GRANT  = 1;
    const AUTH_TYPE_REVOKE = 2;

    // Define Camunda constants for resource types.
    const RESOURCE_PROCESS_DEFINITION = 6;
    const RESOURCE_PROCESS_INSTANCE   = 8;

    public function getAll()
    {
        return $this->client->get('authorization');
    }

    public function get(string $id)
    {
        return $this->client->get("authorization/$id");
    }

    public function for(string $groupId)
    {
        return $this->client->get('authorization', ['groupId' => $groupId]);
    }

    public function getByType(int $id)
    {
        return $this->client->get('authorization', ['resourceType' => $id]);
    }

    public function create(array $data)
    {
        try {
            return $this->client->post('authorization/create', $data);
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException('Authorization could not be created due to an internal server error.');
        }
    }

    public function delete(string $id)
    {
        try {
            return $this->client->delete("authorization/$id");
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException('Authorization could not be deleted due to an internal server error.');
        }
    }
}
