<?php

use App\Models\Lists\Region;
use Illuminate\Database\Seeder;

class RegionTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Atlantic', 'name_fr' => 'Atlantique'],
            ['name_en' => 'Quebec / Nunavut', 'name_fr' => 'Québec/Nunavut'],
            ['name_en' => 'National Capital Region (NCR)', 'name_fr' => 'Région de la capitale nationale (RCN)'],
            ['name_en' => 'Ontario', 'name_fr' => 'Ontario'],
            ['name_en' => 'Prairies', 'name_fr' => 'Prairies'],
            ['name_en' => 'Pacific / Yukon', 'name_fr' => 'Pacifique/Yukon'],
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
        DB::table('regions')->truncate();

        Region::createFrom($this->data());
    }
}
