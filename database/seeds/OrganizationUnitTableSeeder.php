<?php

use Illuminate\Database\Seeder;
use App\OrganizationUnit;

class OrganizationUnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationUnit::truncate();

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 8; $i++) {
            OrganizationUnit::create([
                'name' => $faker->word,
            ]);
        }
    }
}
