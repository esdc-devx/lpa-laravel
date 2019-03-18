<?php

use App\Models\Process\ProcessInstanceFormAssessment;
use App\Models\Process\ProcessInstanceFormAssessmentDataModel;
use App\Models\LearningProduct\Development\DevelopmentAssessment;

$factory->define(DevelopmentAssessment::class, function () {

    $formAssessmentData = factory(ProcessInstanceFormAssessmentDataModel::class)->make();
    return $formAssessmentData->toArray();

});

$factory->afterMaking(DevelopmentAssessment::class, function (&$data) {
    // Since the number of assessments here is based on the learning product type,
    // we need to generate fake assessments based on those defined.
    $formData = DevelopmentAssessment::whereProcessInstanceId($data['process_instance_id'])->firstOrFail();
    $count = count($formData['assessments']);
    $assessments = factory(ProcessInstanceFormAssessment::class, $count)->make()->toArray();
    $data['assessments'] = $assessments;
});
