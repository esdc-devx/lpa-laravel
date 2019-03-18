<?php

use App\Models\Process\ProcessFormDecision;
use App\Models\Process\ProcessInstanceFormAssessment;
use Faker\Generator as Faker;

$factory->define(ProcessInstanceFormAssessment::class, function (Faker $faker) {

    static $decisionId;

    if (! $decisionId) {
        $decisionId = ProcessFormDecision::getIdFromKey('approved');
    }

    return [
        'process_form_decision_id' => $decisionId,
        'comments' => $faker->paragraph(),
    ];

});
