<?php

use App\Models\Lists\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Classroom', 'name_fr' => 'Salle de classe'],
            ['name_en' => 'Computer Lab', 'name_fr' => 'Laboratoire informatique'],
            ['name_en' => 'Lounge', 'name_fr' => 'Salon'],
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
        DB::table('room_types')->truncate();

        RoomType::createFrom($this->data());
    }
}
