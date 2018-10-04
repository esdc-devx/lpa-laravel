<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class LearningProductDeleted
{
    use SerializesModels;

    public $learningProduct;

    /**
     * Create a new event instance.
     *
     * @param App\Models\BaseModel $entity
     */
    public function __construct($entity)
    {
        $this->learningProduct = $entity;
    }
}
