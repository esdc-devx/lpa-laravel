<?php

use App\Models\Lists\Competency;
use Illuminate\Database\Seeder;

class CompetencyTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Demonstrating Integrity and Respect', 'name_fr' => 'Faire preuve d’intégrité et de respect'],
            ['name_en' => 'Thinking Things Through', 'name_fr' => 'Avoir une réflexion approfondie'],
            ['name_en' => 'Working Effectively with Others', 'name_fr' => 'Travailler efficacement avec d’autres personnes'],
            ['name_en' => 'Showing Initiative', 'name_fr' => 'Faire preuve d\'initiative'],
            ['name_en' => 'Being Action-Oriented', 'name_fr' => 'Être orienté vers l’action'],
            ['name_en' => 'Creating Vision and Integrity', 'name_fr' => 'Créer une vision et de l’intégrité'],
            ['name_en' => 'Mobilize People', 'name_fr' => 'Mobiliser les personnes'],
            ['name_en' => 'Uphold Integrity and Respect', 'name_fr' => 'Préserver l’intégrité et le respect'],
            ['name_en' => 'Collaborate with Partners and Stakeholders', 'name_fr' => 'Collaborer avec les partenaires et les intervenants'],
            ['name_en' => 'Promote Innovation and Guide Change', 'name_fr' => 'Promouvoir l’innovation et orienter le changement'],
            ['name_en' => 'Achieve Results Functional Competencies', 'name_fr' => 'Obtenir des compétences fonctionnelles comme résultats'],
            ['name_en' => 'Technical Competencies', 'name_fr' => 'Compétences techniques'],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('design_competency')->truncate();
        DB::table('competencies')->truncate();

        Competency::createFrom($this->data());
    }
}
