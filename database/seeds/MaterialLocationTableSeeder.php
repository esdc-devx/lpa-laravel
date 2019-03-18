<?php

use App\Models\Lists\MaterialLocation;
use Illuminate\Database\Seeder;

class MaterialLocationTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Main Room', 'name_fr' => 'Salle principale'],
            ['name_en' => 'Breakout Room', 'name_fr' => 'Salle d’atelier'],
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
        DB::table('material_locations')->truncate();

        MaterialLocation::createFrom($this->data());
    }
}
