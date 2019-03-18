<?php

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormAssessmentDataModel;
use App\Models\LearningProduct\CommunicationsReview\CommunicationsMaterialAssessment;

$factory->define(CommunicationsMaterialAssessment::class, function () {

    $formAssessmentData = factory(ProcessInstanceFormAssessmentDataModel::class)->make();
    $formAssessmentData->assessments = factory(ProcessInstanceFormAssessment::class, 1)->make()->toArray();
    return $formAssessmentData->toArray();

});
