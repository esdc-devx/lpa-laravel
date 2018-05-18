<?php

namespace App\Process;

use Illuminate\Support\Facades\Facade;

/**
 * @see App\Process\ProcessManager
 */
class ProcessManagerFacade extends Facade {

    protected static function getFacadeAccessor() { return 'process.manager'; }

}
