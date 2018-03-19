<?php

namespace App\Camunda\APIs;

use App\Camunda\Camunda;

class CamundaBaseAPI
{
    protected $camunda;
    protected $client;

    /**
     * Extendable class for all Camunda APIs.
     *
     * @param Camunda $camunda
     */
    public function __construct(Camunda $camunda)
    {
        $this->camunda = $camunda;
        $this->client = $camunda->client();
    }
}
