<?php
namespace App\Camunda;

use GuzzleHttp\Client as HttpClient;
use App\Camunda\Exceptions\GeneralException;

class CamundaConnector
{
    const STATUS_SUCCESS = 200;
    const STATUS_SUCCESS_NO_CONTENT = 204;
    const STATUS_NOT_FOUND = 404;
    const STATUS_ERROR = 500;

    protected $url;
    protected $client;
    protected $options;

    /**
     * Create a connector which handles requests to Camunda Rest API.
     *
     * @param  string  $host
     * @param  string  $port
     * @param  string  $url
     * @return void
     */
    public function __construct($host, $port, $url)
    {
        // Define Camunda API URL.
        $this->url = "$host:$port/$url";

        // Define http client.
        $this->client = new HttpClient();

        // Prevent Guzzle from throwing exceptions when API responses
        // don't return a success code.
        $this->options = ['http_errors' => false];
    }

    /**
     * Handle all request types to Camunda Rest API.
     *
     * @param  string $method
     * @param  string $route
     * @param  array  $data
     * @return void
     */
    protected function request($method, $route, $data = [])
    {
        $response = $this->client->request(
            $method,
            "$this->url/$route",
            array_merge($this->options, $data)
        );

        // Make sure no errors were returned from the API.
        $this->validate($response);

        return json_decode($response->getBody());
    }

    /**
     * Handle response errors.
     *
     * @param  Psr\Http\Message\ResponseInterface $response
     * @return void
     */
    protected function validate($response)
    {
        if (!in_array($response->getStatusCode(), [self::STATUS_SUCCESS, self::STATUS_SUCCESS_NO_CONTENT])) {
            $content = json_decode($response->getBody());
            if ($content === null || $response->getHeaderLine('content-type') !== 'application/json') {
                throw new GeneralException();
            } else {
                throw new GeneralException("[{$content->type}] {$content->message}");
            }
        }
    }

    /**
     * Execute GET request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $data
     * @return mixed
     */
    public function get($url, $data = [])
    {
        // @todo: Add support for querystring parameters.
        return $this->request('GET', $url);
    }

    /**
     * Execute POST request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $data
     * @return mixed
     */
    public function post($url, $data)
    {
        return $this->request('POST', $url, ['json' => $data]);
    }

    /**
     * Execute PUT request to Camunda Rest API.
     *
     * @param  string $url
     * @param  array  $data
     * @return mixed
     */
    public function put($url, $data)
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
