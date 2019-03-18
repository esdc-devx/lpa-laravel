<?php

use App\Models\Project\Project;
use App\Models\State;
use App\Support\Facades\ProcessFactory;
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
        'outline'                => $faker->paragraph(),
        'state_id'               => $stateId,
        'created_by'             => $faker->randomUserId(),
        'updated_by'             => $faker->randomUserId(),
    ];
});

// Add factory state to create a project and start its approval process.
$factory->afterCreatingState(Project::class, 'process:project-approval', function ($project) {
    ProcessFactory::startProcess('project-approval', $project)->complete();
});
