<?php

namespace App\Events\Process;

use App\Models\Process\ProcessInstance;
use Illuminate\Queue\SerializesModels;

class ProjectApprovalStarted
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
