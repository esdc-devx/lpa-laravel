<?php

use App\Models\Project\BusinessCase\GovernmentPriority;
use Illuminate\Database\Seeder;

class GovernmentPriorityTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Delivering on Results',
                'name_fr' => 'Exécution de résultats',
            ],
            [
                'name_en' => 'Indigenous Awareness',
                'name_fr' => 'Sensibilité à l’égard des Autochtones',
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
                'name_en' => 'Healthy Workplace (V&E)',
                'name_fr' => 'Milieu de travail sain',
            ],
            [
                'name_en' => 'Performance & Talent Management',
                'name_fr' => 'Gestion du rendement et des talents',
            ],
            [
                'name_en' => 'Service Excellence',
                'name_fr' => 'Excellence en matière de service',
            ],
            [
                'name_en' => 'Change Management',
                'name_fr' => 'Gestion du changement',
            ],
            [
                'name_en' => 'Project Management',
                'name_fr' => 'Gestion de projets',
            ],
            [
                'name_en' => 'Gender-based Analysis',
                'name_fr' => 'Analyse comparative entre les sexes',
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
        DB::table('government_priorities')->truncate();
        DB::table('business_case_government_priority')->truncate();

        foreach ($this->data() as $term) {
            GovernmentPriority::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
