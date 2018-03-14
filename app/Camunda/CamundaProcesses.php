<?php

namespace App\Camunda;

use Illuminate\Support\Facades\Storage;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;

class CamundaProcesses extends CamundaBaseApi
{
    /**
     * Get a process definition from its unique key.
     *
     * @param  string $key
     * @return object
     */
    public function get(string $key)
    {
        return $this->client->get("process-definition/key/$key");
    }

    /**
     * Get a process definition from its id.
     *
     * @param  string $id
     * @return object
     */
    public function getById(string $id)
    {
        return $this->client->get("process-definition/$id");
    }

    /**
     * Get all process definitions.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->client->get('process-definition');
    }

    /**
     * Deploy a process definition from a bpmn file.
     * File location is defined in configuration.
     *
     * @param  array $data
     * @return void
     */
    public function deploy(array $data = [])
    {
        // Ensure that deployment file exists.
        $filePath = $this->config['storage'] . $data['file'];
        if (!Storage::disk('local')->exists($filePath)) {
            throw new GeneralException("Cannot deploy process [{$data['file']}]. Make sure that file exists on the system.");
        }

        // Upload new process to Camunda.
        try {
            return $response = $this->client->post('deployment/create', [
                'multipart' => [
                    [
                        'name'     => 'deployment-name',
                        'contents' => $data['name']
                    ],
                    [
                        'name'     => 'enable-duplicate-filtering',
                        'contents' => false,
                    ],
                    [
                        'name'     => 'deploy-changed-only',
                        'contents' => false,
                    ],
                    [
                        'name'     => 'data',
                        'contents' => Storage::get($filePath),
                        'filename' => $data['file'],
                    ],
                ]
            ]);
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException("User [$user->username] could not be created due to an internal server error.");
        }
    }

    /**
     * Start a new process instance.
     *
     * @param  array $data
     * @return object
     */
    public function start(array $data)
    {
        try {
            // Format post data.
            $postData = [
                'business_key' => $data['business_key'] ?? null,
                'variables'    => isset($data['business_key']) ? json_encode((object) $data['variables']) : null,
            ];

            // Start a specific version of the process definition from its id.
            if (isset($data['id'])) {
                return $this->client->post("process-definition/{$data['id']}/start", $postData);
            }
            // Default to unique key. This will create an instance from the latest definition of the specified process.
            return $this->client->post("process-definition/key/{$data['key']}/start", $postData);
        }
        // Handle Camunda ProcessEngine exceptions.
        catch (ProcessEngineException $e) {
            throw new GeneralException('An error occured while starting new process.');
        }
    }

    /**
     * Retrieve a process instance from its id.
     *
     * @param  string $id
     * @return object
     */
    public function getInstance(string $id)
    {
        return $this->client->get('history/process-instance', ['processInstanceId' => $id]);
    }
}
