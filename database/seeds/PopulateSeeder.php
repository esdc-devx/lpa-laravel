<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PopulateSeeder extends Seeder
{
    /**
     * Seed fake data for testing purposes.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            ProjectTableSeeder::class,
            LearningProductTableSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
