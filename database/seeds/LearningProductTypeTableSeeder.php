<?php

use App\Models\LearningProduct\LearningProductType;
use Illuminate\Database\Seeder;

class LearningProductTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Course',
                'name_fr' => 'Cours',
                'children' => [
                    [
                        'name_en' => 'Instructor-led',
                        'name_fr' => 'Apprentissage avec instructeur',
                    ],
                    [
                        'name_en' => 'Distance Learning',
                        'name_fr' => 'Apprentissage à distance',
                    ],
                    [
                        'name_en' => 'Online Self-Paced',
                        'name_fr' => 'En ligne – à rythme libre',
                    ],
                ],
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate previous tables.
        DB::table('learning_product_types')->truncate();

        LearningProductType::createFrom($this->data());
    }
}
