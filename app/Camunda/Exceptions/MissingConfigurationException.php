<?php

namespace App\Camunda\Exceptions;

use Exception;

class MissingConfigurationException extends Exception
{
    /**
     * Create a missing configuration exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct()
    {
        parent::__construct('Camunda configurations could not be found.');
    }
}
