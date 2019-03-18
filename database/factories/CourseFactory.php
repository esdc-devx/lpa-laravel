<?php

use App\Models\LearningProduct\Course;
use App\Models\LearningProduct\LearningProductType;
use App\Models\State;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {

    static $typeId;
    static $stateId;

    $faker->addProvider(new App\Support\CustomFaker($faker));

    if (! $typeId) {
        $typeId = LearningProductType::getIdFromKey('course');
    }

    if (! $stateId) {
        $stateId = State::getIdFromKey('course.new');
    }

    return [
        'name'                   => $faker->sentenceNoDot(),
        'type_id'                => $typeId,
        'organizational_unit_id' => $faker->randomLearningProductOwnerId(),
        'state_id'               => $stateId,
        'primary_contact'        => $faker->randomUserId(),
        'manager'                => $faker->randomUserId(),
        'created_by'             => $faker->randomUserId(),
        'updated_by'             => $faker->randomUserId(),
    ];

});

// Store course sub type ids using static variables to prevent
// duplicate queries to the database.
function getCourseSubTypeId ($typeKey) {
    static $ids;

    if (! $ids) {
        $ids = [];
    }

    if (! isset($ids[$typeKey])) {
        $ids[$typeKey] = LearningProductType::getIdFromKey($typeKey);
    }

    return $ids[$typeKey];
}

// Add a factory state to generate instructor led course.
$factory->state(Course::class, 'instructor-led', function () {
    return [
        'sub_type_id' => getCourseSubTypeId('instructor-led'),
    ];
});

// Add a factory state to generate distance learning course.
$factory->state(Course::class, 'distance-learning', function () {
    return [
        'sub_type_id' => getCourseSubTypeId('distance-learning'),
    ];
});

// Add a factory state to generate online self paced course.
$factory->state(Course::class, 'online-self-paced', function () {
    return [
        'sub_type_id' => getCourseSubTypeId('online-self-paced'),
    ];
});

// Handle logic after creating a course.
$factory->afterCreating(Course::class, function ($course) {
    // No sub type was defined durint creation, assign random one.
    if (! $course->sub_type_id) {
        $subTypeIds = LearningProductType::getSubTypesFor('course')->pluck('id');
        $course->sub_type_id = $subTypeIds->random();
    }
    // Instantiate a parent project using Process Factory.
    $project = resolve('process.factory')
        ->startProcess('project-approval')
        ->complete()
        ->getProcessEntity();

    // Attach parent project and update organizational unit to fit its parent project.
    $course->project_id = $project->id;
    $course->organizational_unit_id = $project->organizational_unit_id;
    $course->save();
});

// Create a course and complete its course-development process.
// @note: This only works if all form factories are implemendted.
$factory->afterCreatingState(Course::class, 'process:course-development', function ($course) {
    resolve('process.factory')
        ->startProcess('course-development', $course)
        ->complete();
});
