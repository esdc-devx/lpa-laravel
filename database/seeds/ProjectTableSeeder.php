<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\Project\Project;
use App\Models\OrganizationalUnit\OrganizationalUnit;

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
        $organizationalUnitIds = OrganizationalUnit::where('owner', true)->pluck('id');

        for ($i = 0; $i < 50; $i++) {
            Project::create([
                'name' => $faker->sentence,
                'owner_id' => $userIds->random(),
                'organizational_unit_id' => $organizationalUnitIds->random(),
            ]);
        }
    }
}
