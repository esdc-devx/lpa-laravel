<?php

namespace App\Camunda\Exceptions;

use Exception;

class ResourceNotFoundException extends Exception
{
    /**
     * Create a resource not found exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Could not retrieve resource from Camunda Rest API.')
    {
        parent::__construct($message);
    }
}
