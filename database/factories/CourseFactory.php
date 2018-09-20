<?php

use App\Models\LearningProduct\Course;
use App\Models\LearningProduct\LearningProductType;
use App\Models\State;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {

    static $typeId;
    static $subTypeIds;
    static $stateId;

    $faker->addProvider(new App\Support\CustomFaker($faker));

    if (! $typeId) {
        $typeId = LearningProductType::getIdFromKey('course');
    }

    if (! $subTypeIds) {
        $subTypeIds = LearningProductType::where('parent_id', $typeId)->pluck('id');
    }

    if (! $stateId) {
        $stateId = State::getIdFromKey('course.new');
    }

    return [
        'name'                   => $faker->sentenceNoDot(),
        'type_id'                => $typeId,
        'sub_type_id'            => $subTypeIds->random(),
        'organizational_unit_id' => $faker->randomLearningProductOwnerId(),
        'state_id'               => $stateId,
        'created_by'             => $faker->randomUserId(),
        'updated_by'             => $faker->randomUserId(),
    ];

});
