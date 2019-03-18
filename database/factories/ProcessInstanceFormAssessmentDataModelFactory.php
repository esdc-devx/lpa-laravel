<?php

use App\Models\Process\ProcessInstanceFormAssessmentDataModel;
use Faker\Generator as Faker;

$factory->define(ProcessInstanceFormAssessmentDataModel::class, function (Faker $faker) {

    return [
        'is_entity_cancelled' => false,
        'assessment_date'     => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'assessments'         => [],
    ];

});
