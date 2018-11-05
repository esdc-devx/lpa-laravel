<?php

namespace App\Events;

use App\Models\User\User;
use Illuminate\Queue\SerializesModels;

class UserSaved
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param App\Models\User\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
