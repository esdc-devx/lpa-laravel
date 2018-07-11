<?php

namespace App\Events;

use App\Models\Process\ProcessInstanceForm;
use Illuminate\Queue\SerializesModels;

class ProcessInstanceFormSubmitted
{
    use SerializesModels;

    public $processInstanceForm;

    /**
     * Create a new event instance.
     *
     * @param  ProcessInstanceForm $processInstanceForm
     * @return void
     */
    public function __construct(ProcessInstanceForm $processInstanceForm)
    {
        $this->processInstanceForm = $processInstanceForm;
    }
}
