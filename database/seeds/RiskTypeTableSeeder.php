<?php

use App\Models\Lists\RiskType;
use Illuminate\Database\Seeder;

class RiskTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Policy Concerns',
                'name_fr' => 'Enjeux politiques',
            ],
            [
                'name_en' => 'Legal Obligations',
                'name_fr' => 'Obligations légales',
            ],
            [
                'name_en' => 'Mandatory Training',
                'name_fr' => 'Formation obligatoire',
            ],
            [
                'name_en' => 'Department Results Framework',
                'name_fr' => 'Cadre de résultats ministériels',
            ],
            [
                'name_en' => 'Reputation',
                'name_fr' => 'Réputation',
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
        DB::table('risk_types')->truncate();

        RiskType::createFrom($this->data());
    }
}
