<?php

use App\Models\Project\BusinessCase\PotentialSolutionType;
use Illuminate\Database\Seeder;

class PotentialSolutionTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Course - Instructor-led',
                'name_fr' => 'Cours - apprentissage en classe',
            ],
            [
                'name_en' => 'Course - Distance learning',
                'name_fr' => 'Cours - apprentissage à distance',
            ],
            [
                'name_en' => 'Course - Online Self-Paced',
                'name_fr' => 'Cours - apprentissage en ligne adapté au rythme de chacun',
            ],
            [
                'name_en' => 'Video',
                'name_fr' => 'Vidéo',
            ],
            [
                'name_en' => 'Job Aid',
                'name_fr' => 'Aide-mémoire',
            ],
            [
                'name_en' => 'Event',
                'name_fr' => 'Événement',
            ],
            [
                'name_en' => 'eBook',
                'name_fr' => 'Livre numérique',
            ],
            [
                'name_en' => 'Blog',
                'name_fr' => 'Blogue',
            ],
            [
                'name_en' => 'Case Study',
                'name_fr' => 'Étude de cas',
            ],
            [
                'name_en' => 'Quiz',
                'name_fr' => 'Questionnaire',
            ],
            [
                'name_en' => 'Game',
                'name_fr' => 'Jeu',
            ],
            [
                'name_en' => 'Commercial Off-The-Shelf (COTS)',
                'name_fr' => 'Solution commerciale',
            ],
            [
                'name_en' => 'Other Government Departments, Agencies or Bodies Content',
                'name_fr' => 'Contenu d\'autres ministères gouvernementaux, organismes ou institutions',
            ],
            [
                'name_en' => 'Non-Profit or Academic Sector Content',
                'name_fr' => 'Contenu du secteur universitaire ou du secteur sans but lucratif',
            ],
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
        DB::table('potential_solution_types')->truncate();
        DB::table('business_case_potential_solution_type')->truncate();

        foreach ($this->data() as $term) {
            PotentialSolutionType::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
