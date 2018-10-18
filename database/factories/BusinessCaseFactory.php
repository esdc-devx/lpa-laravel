<?php

use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\BusinessCase\DepartmentalResultsFrameworkIndicator;
use App\Models\Project\BusinessCase\InternalResource;
use App\Models\Project\BusinessCase\Recurrence;
use App\Models\Project\BusinessCase\RequestOrigin;
use App\Models\Project\BusinessCase\SchoolPriority;
use Faker\Generator as Faker;

$factory->define(BusinessCase::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'request_origins'                           => $faker->randomMultipleList(RequestOrigin::all()),
        'request_origin_other'                      => $faker->sentenceNoDot(),
        'business_issue'                            => $faker->paragraph(),
        'is_required_training'                      => $faker->boolean(),
        'short_term_learning_response'              => $faker->paragraph(),
        'medium_term_learning_response'             => $faker->paragraph(),
        'long_term_learning_response'               => $faker->paragraph(),
        'school_priorities'                         => $faker->randomMultipleList(SchoolPriority::all()),
        'expected_annual_participant_number'        => $faker->roundNumberBetween(1000, 100000),
        'communities'                               => [1],
        'departmental_results_framework_indicators' => $faker->randomMultipleList(DepartmentalResultsFrameworkIndicator::all()),
        'cost_centre'                               => $faker->costCentre(),
        'spendings'                                 => [
            [
                'internal_resource_id' => $faker->randomList(InternalResource::all()),
                'cost_description'     => $faker->paragraph(),
                'cost'                 => $faker->roundNumberBetween(1000, 100000),
                'recurrence_id'        => $faker->randomList(Recurrence::all()),
                'comments'             => $faker->paragraph(),
            ]
        ],
        'risks'                                     => [],
        'comments'                                  => $faker->paragraph(),
    ];
});
