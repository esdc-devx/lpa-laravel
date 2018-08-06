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

    protected function createTerm($term) {
        return LearningProductType::create([
            'parent_id' => $term['parent_id'] ?? 0,
            'name_key' => str_slug($term['name_en'], '-'),
            'name_en' => $term['name_en'],
            'name_fr' => $term['name_fr'],
        ])->id;
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

        foreach ($this->data() as $term) {
            $termId = $this->createTerm($term);

            if (isset($term['children'])) {
                foreach ($term['children'] as $child) {
                    $child['parent_id'] = $termId;
                    $this->createTerm($child);
                }
            }
        }
    }
}
