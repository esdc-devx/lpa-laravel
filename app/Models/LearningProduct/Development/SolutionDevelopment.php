<?php

namespace App\Models\LearningProduct\Development;

use App\Models\Process\ProcessInstanceFormDataModel;

class SolutionDevelopment extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id',
    ];

    protected $with = [
        //
    ];
}
