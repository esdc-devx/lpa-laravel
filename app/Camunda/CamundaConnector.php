<?php

namespace App\Camunda;

use \Illuminate\Http\Response;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\ProcessEngineException;
use App\Camunda\Exceptions\ResourceNotFoundException;
use Illuminate\Auth\Access\AuthorizationException as AuthorizationException;

class CamundaConnector
{
    protected $url;
    protected $client;
    protected $options;

    /**
     * Create a connector which handles requests to Camunda Rest API.
     *
     * @param  array $config
     * @return void
     */
    public function __construct(array $config)
    {
        // Define Camunda API URL.
        $connection = $config['connection'];
        $this->url = "{$connection['host']}:{$connection['port']}/{$connection['url']}";

        // Define http client.
        $this->client = new HttpClient();
        $this->options = [
            'auth' => [$config['credentials']['username'], $config['credentials']['password']],
        ];
    }

    /**
     * Handle all request types to Camunda Rest API.
     *
     * @param  string $method
     * @param  string $route
     * @param  array  $data
     * @return void
     */
    protected function request(string $method, string $route, array $data = [])
    {
        try {
            $response = $this->client->request(
                $method,
                "$this->url/$route",
                array_merge($this->options, $data)
            );
        }
        // Handle HTTP Exceptions.
        catch (ClientException $e) {
            switch ($e->getResponse()->getStatusCode()) {
                // Invalid credentials.
                case Response::HTTP_UNAUTHORIZED:
                    throw new AuthorizationException("Could not authenticate user [{$this->options['auth'][0]}] to Camunda.");

                // Insufficient priviledges.
                case Response::HTTP_FORBIDDEN:
                    throw new AuthorizationException("Insufficient priviledges. Could not execute method [$method] on route [$route]");

                // Method not allowed.
                case Response::HTTP_BAD_REQUEST:
                    $error = json_decode($e->getResponse()->getBody())->message;
                    throw new GeneralException($error);

                // Resource not found.
                case Response::HTTP_NOT_FOUND:
                    throw new ResourceNotFoundException("Resource not found on route [$route]");

                // Method not allowed.
                case Response::HTTP_METHOD_NOT_ALLOWED:
                    throw new GeneralException("Method [$method] not allowed on route [$route]");

                // Default to GeneralException.
                default:
                    throw new GeneralException("Response with status code [{$e->getResponse()->getStatusCode()}] on route [$route]");
            }
        }
        // Handle Server Exceptions.
        catch (ServerException $e) {
            $error = json_decode($e->getResponse()->getBody());
            throw new ProcessEngineException($error ? $error->message : $e->getMessage());
        }

        return json_decode($response->getBody());
    }

    /**
     * Execute GET request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $query
     * @return mixed
     */
    public function get($url, $query = [])
    {
        return $this->request('GET', $url, ['query' => $query]);
    }

    /**
     * Execute POST request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $data
     * @return mixed
     */
    public function post($url, $data = [])
    {
        // Handle multipart/form-data post.
        if (isset($data['multipart'])) {
            return $this->request('POST', $url, $data);
        }
        // Default to json format.
        return $this->request('POST', $url, ['json' => $data]);
    }

    /**
     * Execute PUT request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $data
     * @return mixed
     */
    public function put($url, $data = [])
    {
        return $this->request('PUT', $url, ['json' => $data]);
    }

    /**
     * Execute DELETE request to Camunda Rest API.
     *
     * @param  string $url
     * @return mixed
     */
    public function delete($url)
    {
        return $this->request('DELETE', $url);
    }
}
