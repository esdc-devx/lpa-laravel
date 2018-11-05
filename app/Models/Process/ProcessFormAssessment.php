<?php

namespace App\Models\Process;

use App\Models\BaseModel;

class ProcessFormAssessment extends BaseModel
{
    protected $fillable = [
        'process_form_id',
        'assessed_process_form',
    ];

    public $timestamps = false;
}
