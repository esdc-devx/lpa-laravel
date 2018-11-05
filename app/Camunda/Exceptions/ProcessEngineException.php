<?php

namespace App\Camunda\Exceptions;

use Exception;

class ProcessEngineException extends Exception
{
    /**
     * Create an internal server error exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Camunda server error.')
    {
        parent::__construct($message);
    }
}
