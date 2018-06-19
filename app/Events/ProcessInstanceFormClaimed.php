<?php

namespace App\Events;

use App\Models\Process\ProcessInstanceForm;
use App\Models\User\User;
use Illuminate\Queue\SerializesModels;

class ProcessInstanceFormClaimed
{
    use SerializesModels;

    public $user;
    public $processInstanceForm;

    /**
     * Create a new event instance.
     *
     * @param  User $user
     * @param  ProcessInstanceForm $processInstanceForm
     * @return void
     */
    public function __construct(User $user, ProcessInstanceForm $processInstanceForm)
    {
        $this->user = $user;
        $this->processInstanceForm = $processInstanceForm;
    }
}
