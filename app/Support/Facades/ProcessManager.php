<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see App\Process\ProcessManager
 */
class ProcessManager extends Facade {

    protected static function getFacadeAccessor() { return 'process.manager'; }

}
