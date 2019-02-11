<?php

namespace App\Models\Process;

use App\Models\BaseModel;

class ProcessDefinitionEntityType extends BaseModel
{
    protected $fillable = [
        'process_definition_id',
        'entity_type',
    ];

    public $timestamps = false;
}
