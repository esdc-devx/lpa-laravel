<?php

use App\Models\Lists\RoomLayout;
use Illuminate\Database\Seeder;

class RoomLayoutTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Table Groups', 'name_fr' => 'Groupes de tables'],
            ['name_en' => 'Rows - Tables and chairs  ', 'name_fr' => 'Rangées - tables et chaises  '],
            ['name_en' => 'Rows - Chairs only', 'name_fr' => 'Rangées - chaises uniquement'],
            ['name_en' => 'U-Shape', 'name_fr' => 'En forme de « U »'],
            ['name_en' => 'Circle - Tables and chairs', 'name_fr' => 'Cercle - tables et chaises'],
            ['name_en' => 'Circle - Chairs only', 'name_fr' => 'Cercle - chaises uniquement'],
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
        DB::table('room_layouts')->truncate();

        RoomLayout::createFrom($this->data());
    }
}
