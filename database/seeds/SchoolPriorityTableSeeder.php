<?php

use App\Models\Lists\SchoolPriority;
use Illuminate\Database\Seeder;

class SchoolPriorityTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Delivering on Results',
                'name_fr' => 'Atteinte des résultats escomptés',
            ],
            [
                'name_en' => 'Digital Government',
                'name_fr' => 'Gouvernement numérique',
            ],
            [
                'name_en' => 'Indigenous Awareness',
                'name_fr' => 'Sensibilisation aux questions autochtones',
            ],
            [
                'name_en' => 'Evidence Based Policy',
                'name_fr' => 'Politiques fondées sur les données probantes',
            ],
            [
                'name_en' => 'Engagement and Communications',
                'name_fr' => 'Mobilisation et communications',
            ],
            [
                'name_en' => 'Open Government',
                'name_fr' => 'Gouvernement ouvert',
            ],
            [
                'name_en' => 'Sustainable Development',
                'name_fr' => 'Développement durable',
            ],
            [
                'name_en' => 'Diversity and Inclusion',
                'name_fr' => 'Diversité et inclusion',
            ],
            [
                'name_en' => 'Healthy and Respectful Workplace',
                'name_fr' => 'Milieu de travail sain',
            ],
            [
                'name_en' => 'Performance & Talent Management',
                'name_fr' => 'Gestion du rendement et des talents',
            ],
            [
                'name_en' => 'Service Excellence',
                'name_fr' => 'Excellence du service',
            ],
            [
                'name_en' => 'Change Management',
                'name_fr' => 'Gestion du changement',
            ],
            [
                'name_en' => 'Project Management',
                'name_fr' => 'Gestion de projet',
            ],
            [
                'name_en' => 'Gender-based Analysis Plus',
                'name_fr' => 'Analyse comparative entre les sexes plus (ACS+)',
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
        DB::table('school_priorities')->truncate();
        DB::table('business_case_school_priority')->truncate();

        SchoolPriority::createFrom($this->data());
    }
}
