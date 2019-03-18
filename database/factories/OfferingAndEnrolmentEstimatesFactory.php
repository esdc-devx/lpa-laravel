<?php

use Faker\Generator as Faker;
use App\Models\LearningProduct\Development\OfferingAndEnrolmentEstimates;
use App\Models\Lists\Region;

$factory->define(OfferingAndEnrolmentEstimates::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'offering_regions' => [
            [
                'region_id'                                                   => $faker->randomList(Region::all()),
                'regional_annual_bilingual_offering_number'                   => $faker->numberBetween(0, 365),
                'regional_annual_english_offering_number'                     => $faker->numberBetween(0, 365),
                'regional_annual_french_offering_number'                      => $faker->numberBetween(0, 365),
                'regional_annual_simultaneous_interpretation_offering_number' => $faker->numberBetween(0, 365),
            ],
        ],
        'estimated_average_annual_participant_number' => $faker->roundNumberBetween(0, 500000),
        'comments'                                    => $faker->paragraph(),
    ];
});
