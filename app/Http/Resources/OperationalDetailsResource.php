<?php

namespace App\Http\Resources;

use App\Models\LearningProduct\Design\Design;

class OperationalDetailsResource extends BaseResource
{
    public function context($request)
    {
        return [
            // Add design form data used to handle specific business logic.
            'design' => Design::whereProcessInstanceId($this->process_instance_id)->firstOrFail(),
        ];
    }
}
