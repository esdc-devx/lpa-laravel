<?php

use App\Models\Lists\CurriculumType;
use Illuminate\Database\Seeder;

class CurriculumTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Digital Academy', 'name_fr' => 'Académie du numérique'],
            ['name_en' => 'GC and Public Sector Skills', 'name_fr' => 'Gouvernement du Canada et compétences du secteur public'],
            ['name_en' => 'Indigenous Learning', 'name_fr' => 'Apprentissages autochtones'],
            ['name_en' => 'Respectful and Inclusive Workplace', 'name_fr' => 'Milieu de travail respectueux et inclusif'],
            ['name_en' => 'Transferable Skills', 'name_fr' => 'Compétences transférables'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('curriculum_types')->truncate();

        CurriculumType::createFrom($this->data());
    }
}
