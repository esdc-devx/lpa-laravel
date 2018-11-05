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
    public function __construct($message = 'Camunda configurations could not be found.')
    {
        parent::__construct($message);
    }
}
