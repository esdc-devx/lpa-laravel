<?php

use App\Models\Project\BusinessCase\BusinessCaseAssessment;
use Faker\Generator as Faker;

$factory->define(BusinessCaseAssessment::class, function (Faker $faker) {

    static $decision;

    if (!$decision) {
        $decision = ProcessFormDecision::getByKey('approved')->first();
    }

    return [
        'is_process_cancelled' => false,
        'assessment_date'      => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'assessments'          => [
            [
                'process_form_decision_id' => $decision->id,
                'comment'                  => $faker->paragraph(),
            ],
        ],
    ];

});
