<?php

use App\Models\Project\BusinessCase\RiskProbabilityLevel;
use Illuminate\Database\Seeder;

class RiskProbabilityLevelTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Low',
                'name_fr' => 'Faible',
            ],
            [
                'name_en' => 'Moderate',
                'name_fr' => 'Modéré',
            ],
            [
                'name_en' => 'High',
                'name_fr' => 'Élevé',
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
        DB::table('risk_probability_levels')->truncate();

        foreach ($this->data() as $term) {
            RiskProbabilityLevel::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
