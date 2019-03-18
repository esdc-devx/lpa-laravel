<?php

use App\Models\Lists\RegistrationMode;
use Illuminate\Database\Seeder;

class RegistrationModeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Allocated', 'name_fr' => 'Désigné'],
            ['name_en' => 'Elective', 'name_fr' => 'Volontaire'],
        ];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //// Truncate previous tables.
        DB::table('registration_modes')->truncate();

        RegistrationMode::createFrom($this->data());
    }
}
