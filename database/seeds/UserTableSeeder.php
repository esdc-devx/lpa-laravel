<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\OrganizationUnit\OrganizationUnit;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $faker = \Faker\Factory::create();
        $organizationUnitIds = OrganizationUnit::all()->pluck('id');

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'name' => "$faker->firstName $faker->lastName",
                'email' => $faker->email,
                'password' => bcrypt($faker->word)
            ]);
            $user->organizationUnits()->sync([$organizationUnitIds->random()]);
        }
    }
}
