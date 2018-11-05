<?php

namespace App\Events;

use App\Models\Process\ProcessInstance;
use Illuminate\Queue\SerializesModels;

class ProcessProjectApprovalCompleted
{
    use SerializesModels;

    public $processInstance;

    /**
     * Create a new event instance.
     *
     * @param  ProcessInstance $processInstance
     * @return void
     */
    public function __construct(ProcessInstance $processInstance)
    {
        $this->processInstance = $processInstance;
    }
}
