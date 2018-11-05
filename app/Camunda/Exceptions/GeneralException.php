<?php

namespace App\Camunda\Exceptions;

use Exception;

class GeneralException extends Exception
{
    /**
     * Create a Camunda general exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'An error occured while reaching Camunda Rest API.')
    {
        parent::__construct($message);
    }
}
