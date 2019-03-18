<?php

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormAssessmentDataModel;
use App\Models\LearningProduct\Design\DesignAssessment;

$factory->define(DesignAssessment::class, function () {

    $formAssessmentData = factory(ProcessInstanceFormAssessmentDataModel::class)->make();
    $formAssessmentData->assessments = factory(ProcessInstanceFormAssessment::class, 1)->make()->toArray();
    return $formAssessmentData->toArray();

});
