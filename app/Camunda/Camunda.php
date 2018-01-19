<?php
namespace App\Camunda;

use Illuminate\Support\Facades\Response;
use App\Camunda\Exceptions\GeneralException;
use App\Camunda\Exceptions\RouteNotFoundException;

class Camunda
{
    protected $guzzle;
    protected $api;
    protected $options;

    /**
     * Create an instance of Camunda manager class.
     *
     * @param  \GuzzleHttp\Client  $guzzle
     * @return void
     */
    public function __construct($guzzle)
    {
        $host = config('camunda.host');
        $port = config('camunda.port');
        $url = config('camunda.url');

        // Prevent Guzzle client from throwing exceptions when api response
        // doesn't return code 200.
        $this->options = ['http_errors' => false];

        $this->api = $host . ':' . $port . $url;
        $this->guzzle = $guzzle;
    }

    /**
     * Helper method which handle Rest API calls to Camunda.
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $data
     * @return void
     */
    protected function request($method, $url, $data = [])
    {
        $response = $this->guzzle->request(
            $method,
            "$this->api/$url",
            array_merge($this->options, $data)
        );

        $content = json_decode($response->getBody());

        // Handle errors.
        if (! in_array($response->getStatusCode(), [200, 204])) {
            if ($content === null || $response->getHeaderLine('content-type') !== 'application/json') {
                throw new GeneralException();
            } else {
                throw new GeneralException("[{$content->type}] {$content->message}");
            }
        }

        return $content;
    }

    /**
     * Execute GET request to Camunda Rest API.
     *
     * @param  string  $url
     * @param  array   $data
     * @return mixed
     */
    protected function get($url, $data = [])
    {
        // @todo: Add support for querystring parameters.
        return $this->request('GET', $url);
    }

    /**
     * Execute POST request to Camunda Rest API.
     *
     * @param  string  $url
     * @param  array   $data
     * @return mixed
     */
    protected function post($url, $data)
    {
        return $this->request('POST', $url, ['json' => $data]);
    }

    /**
     * Execute PUT request to Camunda Rest API.
     *
     * @param  string  $url
     * @param  array   $data
     * @return mixed
     */
    protected function put($url, $data)
    {
        return $this->request('PUT', $url, ['json' => $data]);
    }

    /**
     * Execute DELETE request to Camunda Rest API.
     *
     * @param  string  $url
     * @return mixed
     */
    protected function delete($url)
    {
        return $this->request('DELETE', $url);
    }

    /**
     * Get all users from Camunda.
     *
     * @return array
     */
    public function users()
    {
        return $this->get('user');
    }

    /**
     * Get user profile from Camunda.
     *
     * @param  string  $id
     * @return object
     */
    public function user($id = '')
    {
        return $this->get("user/$id/profile");
    }

    /**
     * Create user profile in Camunda.
     *
     * @param  array  $userInfo
     * @return void
     */
    public function createUser($userInfo = [])
    {
        $this->post('user/create', (object) [
            'profile' => (object) $userInfo
        ]);
    }

    /**
     * Delete user profile in Camunda.
     *
     * @param  string $id
     * @return void
     */
    public function deleteUser($id)
    {
        $this->delete("user/$id");
    }

    /**
     * Update user profile in Camunda.
     *
     * @param  string $id
     * @param  array  $data
     * @return null
     */
    public function updateUser($id, $data)
    {
        $data['id'] = $id;
        return $this->put("user/$id/profile", (object) $data);
    }

    /**
     * Get all groups from Camunda.
     *
     * @return array
     */
    public function groups()
    {
        return $this->get('group');
    }
}
