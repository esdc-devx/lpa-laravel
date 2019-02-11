<?php

namespace App\Models\LearningProduct\Design;

use App\Models\Process\ProcessInstanceFormDataModel;

class Design extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id',
    ];

    protected $with = [
        //
    ];
}
