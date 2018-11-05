<?php

use App\Models\OrganizationalUnit;
use App\Models\Project\Project;
use App\Models\State;
use App\Models\User\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {

    static $stateId;

    $faker->addProvider(new App\Support\CustomFaker($faker));

    if (! $stateId) {
        $stateId = State::getIdFromKey('project.new');
    }

    return [
        'name'                   => $faker->sentenceNoDot(),
        'organizational_unit_id' => $faker->randomLearningProductOwnerId(),
        'state_id'               => $stateId,
        'created_by'             => $faker->randomUserId(),
        'updated_by'             => $faker->randomUserId(),
    ];
});
