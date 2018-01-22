<?php
namespace App\Camunda;

class Camunda
{
    protected $api;

    /**
     * Create an instance of Camunda manager class.
     *
     * @return void
     */
    public function __construct()
    {
        // Fetch Camunda Rest API info from config file.
        $host = config('camunda.host');
        $port = config('camunda.port');
        $url = config('camunda.url');

        $this->api = new CamundaConnector($host, $port, $url);
    }

    /**
     * Get all users from Camunda.
     *
     * @return array
     */
    public function users()
    {
        return $this->api->get('user');
    }

    /**
     * Get user profile from Camunda.
     *
     * @param  string  $id
     * @return object
     */
    public function user($id = '')
    {
        return $this->api->get("user/$id/profile");
    }

    /**
     * Create user profile in Camunda.
     *
     * @param  array  $userInfo
     * @return void
     */
    public function createUser($userInfo = [])
    {
        $this->api->post('user/create', (object) [
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
        $this->api->delete("user/$id");
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
        return $this->api->put("user/$id/profile", (object) $data);
    }

    /**
     * Get all groups from Camunda.
     *
     * @return array
     */
    public function groups()
    {
        return $this->api->get('group');
    }
}
