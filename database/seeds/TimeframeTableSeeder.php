<?php

use App\Models\Project\BusinessCase\Timeframe;
use Illuminate\Database\Seeder;

class TimeframeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => '0-3 months',
                'name_fr' => '0 à 3 mois',
            ],
            [
                'name_en' => '4-7 months',
                'name_fr' => '4 à 7 mois',
            ],
            [
                'name_en' => '8-11 months',
                'name_fr' => '8 à 11 mois',
            ],
            [
                'name_en' => '12 + months',
                'name_fr' => '12 mois ou plus',
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
        DB::table('timeframes')->truncate();

        foreach ($this->data() as $term) {
            Timeframe::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
