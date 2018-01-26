<?php

use Illuminate\Database\Seeder;
use App\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::truncate();

        $faker = \Faker\Factory::create();
        $userIds = App\User::pluck('id');
        $organizationUnitIds = App\OrganizationUnit::pluck('id');

        for ($i = 0; $i < 50; $i++) {
            Project::create([
                'name' => $faker->sentence,
                'owner_id' => $userIds->random(),
                'organization_unit_id' => $organizationUnitIds->random(),
            ]);
        }
    }
}
