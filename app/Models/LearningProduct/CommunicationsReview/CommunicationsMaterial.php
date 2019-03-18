<?php

namespace App\Models\LearningProduct\CommunicationsReview;

use App\Models\Process\ProcessInstanceFormDataModel;

class CommunicationsMaterial extends ProcessInstanceFormDataModel
{
    protected $fillable = [
        'process_instance_form_id',
        'process_instance_id',
        'title_en',
        'title_fr',
        'description_en',
        'description_fr',
        'keywords_en',
        'keywords_fr',
        'note_en',
        'note_fr',
        'disclaimer_en',
        'disclaimer_fr',
        'summary_en',
        'summary_fr',
        'comments',
    ];

    protected $with = [
        //
    ];
}
