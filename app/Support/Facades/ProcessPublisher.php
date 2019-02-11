<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see App\Process\ProcessPublisher
 */
class ProcessPublisher extends Facade {

    protected static function getFacadeAccessor() { return 'process.publisher'; }

}
