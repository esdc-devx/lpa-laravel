<?php

use App\Models\Process\ProcessFormDecision;
use App\Models\Project\GateOneApproval\GateOneApproval;
use Faker\Generator as Faker;

$factory->define(GateOneApproval::class, function (Faker $faker) {

    static $decision;

    if (! $decision) {
        $decision = ProcessFormDecision::getByKey('approved')->first();
    }

    return [
        'is_entity_cancelled'  => false,
        'assessment_date'      => $faker->dateTimeThisMonth('now')->format('Y-m-d H:i:s'),
        'assessments'          => [
            [
                'process_form_decision_id' => $decision->id,
                'comments'                 => $faker->paragraph(),
            ],
            [
                'process_form_decision_id' => $decision->id,
                'comments'                 => $faker->paragraph(),
            ],
        ],
    ];

});
