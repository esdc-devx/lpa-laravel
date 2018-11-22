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

        // Turn off process notifications during seeding process.
        config(['mail.send_process_notifications' => false]);

        $this->call([
            ProjectTableSeeder::class,
            LearningProductTableSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
