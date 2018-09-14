<?php

use App\Models\OrganizationalUnit;
use App\Models\Project\Project;
use App\Models\State;
use App\Models\User\User;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {

    static $organizationalUnitIds;
    static $userIds;
    static $stateId;

    $faker->addProvider(new App\Support\CustomFaker($faker));

    if (! $organizationalUnitIds) {
        $organizationalUnitIds = OrganizationalUnit::where('owner', true)->pluck('id');
    }

    if (! $userIds) {
        $userIds = User::pluck('id');
    }

    if (! $stateId) {
        $stateId = State::getByKey('project.new')->first()->id;
    }

    return [
        'name'                   => $faker->sentenceNoDot(),
        'organizational_unit_id' => $organizationalUnitIds->random(),
        'state_id'               => $stateId,
        'created_by'             => $userIds->random(),
        'updated_by'             => $userIds->random(),
    ];
});
