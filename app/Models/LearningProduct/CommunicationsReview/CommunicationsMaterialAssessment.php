<?php

namespace App\Models\LearningProduct\CommunicationsReview;

use App\Models\Process\ProcessInstanceFormAssessmentDataModel;

class CommunicationsMaterialAssessment extends ProcessInstanceFormAssessmentDataModel
{
    public function getAllowEntityCancellationAttribute()
    {
        return false;
    }
}
