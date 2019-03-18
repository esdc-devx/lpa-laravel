<?php

use App\Models\Lists\DocumentDenominator;
use Illuminate\Database\Seeder;

class DocumentDenominatorTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Room', 'name_fr' => 'Salle'],
            ['name_en' => 'Table', 'name_fr' => 'Tableau'],
            ['name_en' => 'Instructor', 'name_fr' => 'Instructeur'],
            ['name_en' => 'Participant', 'name_fr' => 'Participant'],
            ['name_en' => 'Instructor & Participant', 'name_fr' => 'Instructeur et participant'],
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
        DB::table('document_denominators')->truncate();

        DocumentDenominator::createFrom($this->data());
    }
}
