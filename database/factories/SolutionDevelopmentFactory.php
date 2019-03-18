<?php

use App\Models\LearningProduct\Development\SolutionDevelopment;
use App\Models\Lists\ContentProvider;
use Faker\Generator as Faker;

$factory->define(SolutionDevelopment::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'design_provider_id'                  => $faker->randomList(ContentProvider::all()),
        'implementation_provider_id'          => $faker->randomList(ContentProvider::all()),
        'effort_required'                     => $faker->roundNumberBetween(0, 10000),
        'language_content_qa_completed'       => true,
        'instructional_designer_qa_completed' => true,
        'functional_qa_completed'             => true,
        'accessibility_qa_completed'          => true,
        'client_acceptance_test_completed'    => true,
        'comments'                            => $faker->paragraph(),
    ];
});
