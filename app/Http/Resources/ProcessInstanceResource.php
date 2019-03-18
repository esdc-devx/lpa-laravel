<?php

namespace App\Http\Resources;

class ProcessInstanceResource extends BaseResource
{
    public function context($request)
    {
        return [
            'process_entity' => $this->entity
        ];
    }
}
