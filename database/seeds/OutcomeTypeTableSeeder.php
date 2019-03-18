<?php

use App\Models\Lists\OutcomeType;
use Illuminate\Database\Seeder;

class OutcomeTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Awareness', 'name_fr' => 'Sensibilisation'],
            ['name_en' => 'Knowledge', 'name_fr' => 'Connaissances'],
            ['name_en' => 'Skill', 'name_fr' => 'Compétences'],
            ['name_en' => 'Behaviour', 'name_fr' => 'Comportement'],
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
        DB::table('design_outcome_type')->truncate();
        DB::table('outcome_types')->truncate();

        OutcomeType::createFrom($this->data());
    }
}
