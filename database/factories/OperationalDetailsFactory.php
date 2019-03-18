<?php

use App\Models\LearningProduct\Development\OperationalDetails;
use App\Models\Lists\RoomLayout;
use App\Models\Lists\RoomType;
use App\Models\Lists\RoomUsage;
use Faker\Generator as Faker;

$factory->define(OperationalDetails::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'comments'           => $faker->paragraph(),
        'documents'          => [],
        'external_delivery'  => $faker->boolean(),
        'disclaimer_content' => $faker->paragraph(),
        'note_content'       => $faker->paragraph(),
        'summary_content'    => $faker->paragraph(),
        'guest_speakers'     => [],
        'instructors'        => [
            [
                'required_profile_en' => $faker->paragraph(),
                'required_profile_fr' => $faker->paragraph(),
                'schedule_en'         => $faker->paragraph(),
                'schedule_fr'         => $faker->paragraph(),
            ],
        ],
        'main_room_layout_other'                     => $faker->sentenceNoDot(),
        'main_room_special_requirement_description'  => $faker->paragraph(),
        'materials'                                  => [],
        'maximum_number_of_participant_per_offering' => $faker->numberBetween(1, 100),
        'minimum_number_of_participant_per_offering' => $faker->numberBetween(1, 100),
        'number_of_virtual_producers_per_offering'   => $faker->numberBetween(0, 10),
        'optimal_number_of_participant_per_offering' => $faker->numberBetween(1, 100),
        'rooms'                                      => [
            [
                'quantity'                           => $faker->numberBetween(1, 10),
                'room_usage_id'                      => $faker->randomList(RoomUsage::all()),
                'room_type_id'                       => $faker->randomList(RoomType::all()),
                'room_layout_id'                     => $faker->randomList(RoomLayout::all()),
                'special_requirement_description_en' => $faker->paragraph(),
                'special_requirement_description_fr' => $faker->paragraph(),
            ],
        ],
        'session_template'          => $faker->sentenceNoDot(),
        'should_be_published'       => $faker->boolean(),
        'waiting_list_maximum_size' => $faker->numberBetween(0, 99),
    ];
});
