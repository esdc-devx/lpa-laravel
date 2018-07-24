<?php

use App\Models\Project\BusinessCase\LearnersBenefitType;
use Illuminate\Database\Seeder;

class LearnersBenefitTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Access',
                'name_fr' => 'Accès',
            ],
            [
                'name_en' => 'Productivity',
                'name_fr' => 'Productivité',
            ],
            [
                'name_en' => 'Retention',
                'name_fr' => 'Rétention',
            ],
            [
                'name_en' => 'Well Being',
                'name_fr' => 'Bien-être',
            ],
            [
                'name_en' => 'Policy Support',
                'name_fr' => 'Soutien de la politique',
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
        DB::table('learners_benefit_types')->truncate();

        foreach ($this->data() as $term) {
            LearnersBenefitType::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
