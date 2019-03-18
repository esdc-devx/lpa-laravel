<?php

use App\Models\Lists\Topic;
use Illuminate\Database\Seeder;

class TopicTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Communications',
                'name_fr' => 'Communications',
            ],
            [
                'name_en' => 'Evaluation',
                'name_fr' => 'Évaluation',
            ],
            [
                'name_en' => 'Finance',
                'name_fr' => 'Finance',
            ],
            [
                'name_en'  => 'Government Priorities',
                'name_fr'  => 'Priorités gouvernementales',
                'children' => [
                    [
                        'name_en' => 'Healthy and Respectful Workplaces',
                        'name_fr' => 'Milieux de travail sains et respectueux',
                    ],
                    [
                        'name_en' => 'Official Languages',
                        'name_fr' => 'Langues officielles',
                    ],
                    [
                        'name_en' => 'Service Excellence',
                        'name_fr' => 'Excellence du service',
                    ],
                    [
                        'name_en' => 'Values and Ethics',
                        'name_fr' => 'Valeurs et éthique',
                    ],
                ],
            ],
            [
                'name_en' => 'How Government Works',
                'name_fr' => 'Les rouages du gouvernement',
            ],
            [
                'name_en'  => 'Human Resources',
                'name_fr'  => 'Ressources humaines',
                'children' => [
                    [
                        'name_en' => 'Building the Workforce',
                        'name_fr' => 'Bâtir l’effectif',
                    ],
                    [
                        'name_en' => 'Operational Effectiveness',
                        'name_fr' => 'Efficacité opérationnelle',
                    ],
                    [
                        'name_en' => 'Talent Management',
                        'name_fr' => 'Gestion des talents',
                    ],
                    [
                        'name_en' => 'Workplace Management',
                        'name_fr' => 'Gestion du milieu de travail',
                    ],
                ],
            ],
            [
                'name_en' => 'Information Management',
                'name_fr' => 'Gestion de l\'information',
            ],
            [
                'name_en' => 'Information Technology',
                'name_fr' => 'Technologie de l\’information',
            ],
            [
                'name_en' => 'Leadership',
                'name_fr' => 'Leadership',
            ],
            [
                'name_en'  => 'Personal Development',
                'name_fr'  => 'Perfectionnement personnel',
                'children' => [
                    [
                        'name_en' => 'Career Development',
                        'name_fr' => 'Perfectionnement professionnel',
                    ],
                    [
                        'name_en' => 'Communication Skills',
                        'name_fr' => 'Compétences en communication',
                    ],
                    [
                        'name_en' => 'Personal and Team Development',
                        'name_fr' => 'Perfectionnement personnel et perfectionnement de l\’équipe',
                    ],
                ]
            ],
            [
                'name_en' => 'Policy & Regulation',
                'name_fr' => 'Politiques et réglementation',
            ],
            [
                'name_en' => 'Procurement',
                'name_fr' => 'Acquisition',
            ],
            [
                'name_en' => 'Security',
                'name_fr' => 'Sécurité',
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
        DB::table('design_topic')->truncate();
        DB::table('topics')->truncate();

        Topic::createFrom($this->data());
    }
}
