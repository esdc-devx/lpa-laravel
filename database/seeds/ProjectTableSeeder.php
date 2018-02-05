<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Project\Project;
use App\Models\OrganizationUnit\OrganizationUnit;

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
        $userIds = User::pluck('id');
        $organizationUnitIds = OrganizationUnit::where('owner', true)->pluck('id');

        for ($i = 0; $i < 50; $i++) {
            Project::create([
                'name' => $faker->sentence,
                'owner_id' => $userIds->random(),
                'organization_unit_id' => $organizationUnitIds->random(),
            ]);
        }
    }
}
