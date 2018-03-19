<?php

namespace App\Camunda;

use App\Camunda\APIs\CamundaAuthorizations;
use App\Camunda\APIs\CamundaGroups;
use App\Camunda\APIs\CamundaProcesses;
use App\Camunda\APIs\CamundaTasks;
use App\Camunda\APIs\CamundaUsers;
use App\Camunda\Exceptions\InvalidUserProxyException;
use App\Camunda\Exceptions\MissingConfigurationException;

class Camunda
{
    protected $config;
    protected $client;

    /**
     * Create an instance of Camunda service.
     *
     * @return void
     */
    public function __construct($config = null)
    {
        if (is_null($config)) {
            throw new MissingConfigurationException('Must resolve Camunda from service container.');
        }
        $this->config = $config;
        $this->client = $this->auth('admin')->connector();
    }

    /**
     * Create new instance of CamundaConnector.
     *
     * @return CamundaConnector
     */
    protected function connector()
    {
        return new CamundaConnector($this->config);
    }

    /**
     * Return http client instance.
     *
     * @return CamundaConnector
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Return configurations using "dot" notation.
     *
     * @param  string $key
     * @return array
     */
    public function config($key = '*')
    {
        return data_get($this->config, $key);
    }

    /**
     * Update configuration settings using "dot" notation.
     *
     * @param  string $key
     * @param  mixed $value
     * @return $this
     */
    protected function setConfig($key, $value)
    {
        data_set($this->config, $key, $value);
        return $this;
    }

    /**
     * Configure API authentication credentials.
     *
     * @param  string $username
     * @return $this
     */
    protected function auth(string $username = 'admin')
    {
        return $this->setConfig('credentials', [
            'username' => $username === 'admin' ? $this->config('authentication.username') : $username,
            'password' => $username === 'admin' ? $this->config('authentication.password') : $this->password($username)
        ]);
    }

    /**
     * Generate hashed password from username.
     *
     * @param  string $username
     * @return string
     */
    public function password(string $username)
    {
        return crypt($username, $this->config('authentication.salt'));
    }

    /**
     * Allows to excute calls to Camunda API as admin or a specific user.
     *
     * @param  mixed $proxy
     * @return $this
     */
    public function as($proxy = null)
    {
        switch ($type = gettype($proxy)) {
            case 'string':
                // Authenticate as admin account.
                if ($proxy === 'admin') {
                    $this->client = $this->auth('admin')->connector();
                    break;
                }
                // Authenticate as current user.
                if ($proxy === 'current') {
                    $this->client = $this->auth(auth()->user()->username)->connector();
                    break;
                }
                throw new InvalidUserProxyException();

            case 'object':
                // Ensure that object passed is an instance of the user model class.
                if (get_class($proxy) !== $this->config('app.user_model')) {
                    throw new InvalidUserProxyException();
                }
                // Authenticate as proxy user.
                $this->client = $this->auth($proxy->username)->connector();
                break;

            default:
                throw new InvalidUserProxyException();
        }

        return $this;
    }

    /**
     * Return new instance of Camunda users API.
     *
     * @return CamundaUsers
     */
    public function users()
    {
        return new CamundaUsers($this);
    }

    /**
     * Return new instance of Camunda groups API.
     *
     * @return CamundaGroups
     */
    public function groups()
    {
        return new CamundaGroups($this);
    }

    /**
     * Return new instance of Camunda tasks API.
     *
     * @return CamundaTasks
     */
    public function tasks()
    {
        return new CamundaTasks($this);
    }

    /**
     * Return new instance of Camunda authorizations API.
     *
     * @return CamundaAuthorizations
     */
    public function authorizations()
    {
        return new CamundaAuthorizations($this);
    }

    /**
     * Return new instance of Camunda processes API.
     *
     * @return CamundaProcesses
     */
    public function processes()
    {
        return new CamundaProcesses($this);
    }
}
