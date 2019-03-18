<?php

use App\Models\Lists\PossibleOfferingType;
use Illuminate\Database\Seeder;

class PossibleOfferingTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'In-Person', 'name_fr' => 'En personne'],
            ['name_en' => 'Virtual', 'name_fr' => 'Virtuelle'],
            ['name_en' => 'Simultaneous Virtual and In-Person', 'name_fr' => 'En personne et virtuelle simultanément'],
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
        DB::table('design_possible_offering_type')->truncate();
        DB::table('possible_offering_types')->truncate();

        PossibleOfferingType::createFrom($this->data());
    }
}
