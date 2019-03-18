<?php

use App\Models\LearningProduct\CommunicationsReview\CommunicationsMaterial;
use Faker\Generator as Faker;

$factory->define(CommunicationsMaterial::class, function (Faker $faker) {

    $faker->addProvider(new App\Support\CustomFaker($faker));

    return [
        'title_en'       => $faker->paragraph(),
        'title_fr'       => $faker->paragraph(),
        'description_en' => $faker->paragraph(),
        'description_fr' => $faker->paragraph(),
        'keywords_en'    => $faker->paragraph(),
        'keywords_fr'    => $faker->paragraph(),
        'note_en'        => $faker->paragraph(),
        'note_fr'        => $faker->paragraph(),
        'disclaimer_en'  => $faker->paragraph(),
        'disclaimer_fr'  => $faker->paragraph(),
        'summary_en'     => $faker->paragraph(),
        'summary_fr'     => $faker->paragraph(),
        'comments'       => $faker->paragraph(),
    ];
});
