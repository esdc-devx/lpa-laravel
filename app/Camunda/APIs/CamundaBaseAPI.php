<?php

namespace App\Camunda\APIs;

use App\Camunda\Camunda;

class CamundaBaseAPI
{
    protected $config;
    protected $client;
    protected $username;

    /**
     * Extendable class for all Camunda APIs.
     *
     * @param Camunda $camunda
     */
    public function __construct(Camunda $camunda)
    {
        $this->config = $camunda->config();
        $this->client = $camunda->client();
        $this->username = $this->config['credentials']['username'];
    }
}
