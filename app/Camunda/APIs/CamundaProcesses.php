<?php

namespace App\Camunda\APIs;

use Illuminate\Support\Facades\Storage;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;

class CamundaProcesses extends CamundaBaseAPI
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
        $filePath = "{$this->camunda->config('app.storage.deployment')}/{$data['file']}";
        if (! Storage::disk('local')->exists($filePath)) {
            throw new GeneralException("Could not deploy process [{$data['file']}]. Make sure that file exists on the system.");
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
                'businessKey' => $data['business_key'] ?? null,
                'variables'   => $data['variables'] ?? null,
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
        // When calling the history API, Camunda response is an array, so we return the first process instance object.
        return collect($this->client->get('history/process-instance', ['processInstanceId' => $id]))->first();
    }

    /**
     * Delete a process instance from its id.
     *
     * @param  string $id
     * @return void
     */
    public function delete(string $id)
    {
        return $this->client->delete("process-instance/{$id}");
    }

    /**
     * Retrieve process instance variables from its id.
     *
     * @param  string $id
     * @return array
     */
    public function getVariables(string $id)
    {
        return (array) $this->client->get('history/variable-instance', ['processInstanceId' => $id]);
    }

    /**
     * Update process instance variables.
     *
     * @param  string $id
     * @param  array $variables
     * @return void
     */
    public function updateVariables(string $id, array $variables)
    {
        return $this->client->post("process-instance/{$id}/variables", [
            'modifications' => $variables
        ]);
    }
}
