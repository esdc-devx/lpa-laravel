<?php

use Illuminate\Database\Seeder;
use App\Models\User\User;

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

        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'username' => $faker->userName,
                'name' => "$faker->firstName $faker->lastName",
                'email' => $faker->email,
                'password' => bcrypt($faker->word)
            ]);
        }
    }
}
