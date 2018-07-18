<?php

use App\Models\Project\BusinessCase\DepartmentalBenefitType;
use Illuminate\Database\Seeder;

class DepartmentalBenefitTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Cost',
                'name_fr' => 'Coût',
            ],
            [
                'name_en' => 'Delivery',
                'name_fr' => 'Prestation',
            ],
            [
                'name_en' => 'Offering',
                'name_fr' => 'Offre',
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
        DB::table('departmental_benefit_types')->truncate();

        foreach ($this->data() as $term) {
            DepartmentalBenefitType::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
