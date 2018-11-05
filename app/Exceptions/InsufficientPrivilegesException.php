<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class InsufficientPrivilegesException extends AuthorizationException
{
    /**
     * Exception thrown when user doesn't have the right access
     * to accomplish a certain action.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = null)
    {
        parent::__construct($message ?? __('errors.insufficient_privileges'));
    }
}
