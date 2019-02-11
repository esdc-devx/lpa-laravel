<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ProcessDeployed
{
    use SerializesModels;

    public $processDefinition;

    /**
     * Create a new event instance.
     *
     * @param App\Models\Process\ProcessDefinition $processDefinition
     */
    public function __construct($processDefinition)
    {
        $this->processDefinition = $processDefinition;
    }
}
