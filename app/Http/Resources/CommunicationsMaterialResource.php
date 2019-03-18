<?php

namespace App\Http\Resources;

use App\Models\LearningProduct\Design\Design;
use App\Models\LearningProduct\Development\OperationalDetails;

class CommunicationsMaterialResource extends BaseResource
{
    public function context($request)
    {
        return [
            // Add operational details and design form data used to handle specific business logic.
            'operational_details' => OperationalDetails::whereProcessInstanceId($this->process_instance_id)->firstOrFail(),
            'design'              => Design::whereProcessInstanceId($this->process_instance_id)->firstOrFail(),
        ];
    }
}
