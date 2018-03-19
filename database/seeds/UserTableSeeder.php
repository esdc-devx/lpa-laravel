<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\OrganizationalUnit\OrganizationalUnit;

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
        $organizationalUnitIds = OrganizationalUnit::all()->pluck('id');

        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => bcrypt($faker->word)
            ]);
            $user->organizationalUnits()->sync([$organizationalUnitIds->random()]);
        }
    }
}
