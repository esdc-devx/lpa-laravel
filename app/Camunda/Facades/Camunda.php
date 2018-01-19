<?php

namespace App\Camunda\Facades;

use Illuminate\Support\Facades\Facade;

class Camunda extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Camunda';
    }
}
