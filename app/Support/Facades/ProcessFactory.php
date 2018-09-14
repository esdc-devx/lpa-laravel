<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see App\Process\ProcessFactory
 */
class ProcessFactory extends Facade {

    protected static function getFacadeAccessor() { return 'process.factory'; }

}
