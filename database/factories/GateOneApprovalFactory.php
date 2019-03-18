<?php

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormAssessmentDataModel;
use App\Models\Project\GateOneApproval\GateOneApproval;

$factory->define(GateOneApproval::class, function () {

    $formAssessmentData = factory(ProcessInstanceFormAssessmentDataModel::class)->make();
    $formAssessmentData->assessments = factory(ProcessInstanceFormAssessment::class, 2)->make()->toArray();
    return $formAssessmentData->toArray();

});
