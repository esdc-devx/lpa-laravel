<?php

namespace App\Camunda\Exceptions;

use Exception;

class InvalidUserProxyException extends Exception
{
    /**
     * Create an invalid user proxy exception.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Invalid user proxy. User is either invalid or does not exist.')
    {
        parent::__construct($message);
    }
}
