<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class OperationDeniedException extends AuthorizationException
{
    /**
     * Exception thrown when a rule fails on a policy action.
     *
     * @param  string $message
     * @return void
     */
    public function __construct($message = null)
    {
        parent::__construct($message ?? __('errors.operation_denied'));
    }
}
