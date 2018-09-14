<?php

use App\Models\Project\BusinessCase\BusinessCase;
use App\Models\Project\BusinessCase\GovernmentPriority;
use App\Models\Project\BusinessCase\InternalResource;
use App\Models\Project\BusinessCase\MaintenanceFund;
use App\Models\Project\BusinessCase\PotentialSolutionType;
use App\Models\Project\BusinessCase\RequestSource;
use App\Models\Project\BusinessCase\SalaryFund;
use App\Models\Project\BusinessCase\Timeframe;
use Faker\Generator as Faker;

$factory->define(BusinessCase::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'request_sources'                    => $faker->randomMultipleList(RequestSource::all()),
        'request_source_other'               => $faker->sentenceNoDot(),
        'business_issue'                     => $faker->paragraph(),
        'is_required_training'               => $faker->boolean(),
        'learning_response_strategy'         => $faker->paragraph(),
        'potential_solution_types'           => $faker->randomMultipleList(PotentialSolutionType::all()),
        'potential_solution_type_other'      => $faker->sentenceNoDot(),
        'government_priorities'              => $faker->randomMultipleList(GovernmentPriority::all()),
        'expected_annual_participant_number' => $faker->roundNumberBetween(1000, 100000),
        'communities'                        => [1],
        'timeframe_id'                       => $faker->randomList(Timeframe::all()),
        'timeframe_rationale'                => $faker->paragraph(),
        'departmental_benefits'              => [],
        'learners_benefits'                  => [],
        'cost_center'                        => $faker->costCenter(),
        'maintenance_fund_id'                => $faker->randomList(MaintenanceFund::all()),
        'maintenance_fund_rationale'         => $faker->paragraph(),
        'salary_fund_id'                     => $faker->randomList(SalaryFund::all()),
        'salary_fund_rationale'              => $faker->paragraph(),
        'internal_resources'                 => $faker->randomMultipleList(InternalResource::all()),
        'risks'                              => [],
        'comment'                            => $faker->paragraph(),
    ];
});
