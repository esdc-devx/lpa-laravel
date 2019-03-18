<?php

use App\Models\LearningProduct\LearningProduct;
use App\Models\LearningProduct\LearningProductType;
use App\Models\Project\Project;
use App\Models\State;
use Illuminate\Database\Seeder;

class LearningProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LearningProduct::truncate();

        // Creating a course from factory will also generate
        // its parent project approved.
        factory(entity_class('course'), 10)->create();
    }
}
