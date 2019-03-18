<?php

use App\Models\LearningProduct\LearningProductType;
use App\Models\Project\PlannedProductList\PlannedProductList;
use Faker\Generator as Faker;

$factory->define(PlannedProductList::class, function (Faker $faker) {

    static $learningProductTypes;

    $faker->addProvider(new App\Support\CustomFaker($faker));

    if (! $learningProductTypes) {
        // Gather all product sub types.
        $learningProductTypes = LearningProductType::subTypes()->get();
    }

    // Get a random number of product types as planned products.
    $plannedProducts = [];
    $learningProductTypes->each(function ($product) use (&$plannedProducts) {
        $plannedProducts[] = [
            'type_id'     => $product->parent_id,
            'sub_type_id' => $product->id,
            'quantity'    => 1,
        ];
    });

    return [
        'planned_products' => $plannedProducts,
        'comments'         => $faker->paragraph(),
    ];
});
