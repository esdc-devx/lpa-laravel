<?php

use App\Models\Process\ProcessFormDecision;
use App\Models\Project\ArchitecturePlan\ArchitecturePlanAssessment;
use Faker\Generator as Faker;

$factory->define(ArchitecturePlanAssessment::class, function (Faker $faker) {

    static $decision;

    if (! $decision) {
        $decision = ProcessFormDecision::getByKey('approved')->first();
    }

    return [
        'is_process_cancelled' => false,
        'assessment_date'      => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'assessments'          => [
            [
                'process_form_decision_id' => $decision->id,
                'comments'                 => $faker->paragraph(),
            ],
        ],
    ];

});
