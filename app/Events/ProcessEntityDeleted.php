<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ProcessEntityDeleted
{
    use SerializesModels;

    public $entity;

    /**
     * Create a new event instance.
     *
     * @param App\Models\BaseModel $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }
}
