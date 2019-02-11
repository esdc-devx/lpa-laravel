<?php

namespace App\Models\LearningProduct\CommunicationsReview;

use App\Models\Process\ProcessInstanceFormDataModel;

class CommunicationsMaterial extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id',
    ];

    protected $with = [
        //
    ];
}
