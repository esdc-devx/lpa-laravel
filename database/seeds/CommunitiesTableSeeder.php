<?php

use Illuminate\Database\Seeder;
use App\Models\Community;

class CommunitiesTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Employees (All Public Servants)',
                'name_fr' => 'Employés (Tous les fonctionnaires)',
                'name_key' => 'employees',
            ],
            [
                'name_en' => 'Managers & Supervisors',
                'name_fr' => 'Gestionnaires et superviseurs',
                'children' => [
                    [
                        'name_en' => 'Managers',
                        'name_fr' => 'Gestionnaires',
                    ],
                    [
                        'name_en' => 'Supervisors',
                        'name_fr' => 'Superviseurs',
                    ],
                ],
            ],
            [
                'name_en' => 'Executives',
                'name_fr' => 'Cadres',
                'children' => [
                    [
                        'name_en' => 'Deputy Minister',
                        'name_fr' => 'Sous-ministre',
                    ],
                    [
                        'name_en' => 'Directors',
                        'name_fr' => 'Directeurs',
                    ],
                    [
                        'name_en' => 'Directors General',
                        'name_fr' => 'Directeurs généraux',
                    ],
                    [
                        'name_en' => 'Assistant Deputy Ministers',
                        'name_fr' => 'Sous-ministre adjoints',
                    ],
                ],
            ],
            [
                'name_en' => 'Functional Specialists',
                'name_fr' => 'Spécialistes fonctionnels',
                'children' => [
                    [
                        'name_en' => 'Access to Information and Privacy Specialists',
                        'name_fr' => 'Spécialistes de l’accès à l’information et de la protection des renseignements personnels',
                    ],
                    [
                        'name_en' => 'Communications Specialists',
                        'name_fr' => 'Spécialistes en communications',
                    ],
                    [
                        'name_en' => 'Service Specialists',
                        'name_fr' => 'Spécialistes du service',
                    ],
                    [
                        'name_en' => 'Evaluators',
                        'name_fr' => 'Évaluateurs',
                    ],
                    [
                        'name_en' => 'Financial Officers',
                        'name_fr' => 'Agents financiers',
                    ],
                    [
                        'name_en' => 'Human Resources Professionals',
                        'name_fr' => 'Professionnels en ressources humaines',
                    ],
                    [
                        'name_en' => 'Information Management Specialists',
                        'name_fr' => 'Spécialistes en gestion de l’information',
                    ],
                    [
                        'name_en' => 'Internal Auditors',
                        'name_fr' => 'Vérificateurs internes',
                    ],
                    [
                        'name_en' => 'Information Technology Specialists',
                        'name_fr' => 'Spécialistes en technologies de l’information',
                    ],
                    [
                        'name_en' => 'Materiel Management Specialists',
                        'name_fr' => 'Spécialistes de la gestion du matériel',
                    ],
                    [
                        'name_en' => 'Policy Specialists',
                        'name_fr' => 'Spécialistes des politiques',
                    ],
                    [
                        'name_en' => 'Procurement Specialists',
                        'name_fr' => 'Spécialistes des acquisitions',
                    ],
                    [
                        'name_en' => 'Security Specialists',
                        'name_fr' => 'Spécialistes en sécurité',
                    ],
                    [
                        'name_en' => 'Real Property Specialists',
                        'name_fr' => 'Spécialistes des biens immobliers',
                    ],
                    [
                        'name_en' => 'Regulators',
                        'name_fr' => 'Régulateurs',
                    ],
                ],
            ],
        ];
    }

    protected function createTerm($term) {
        return Community::create([
            'parent_id' => $term['parent_id'] ?? 0,
            'name_key'  => $term['name_key'] ?? str_slug($term['name_en'], '-'),
            'name_en'   => $term['name_en'],
            'name_fr'   => $term['name_fr'],
        ])->id;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate previous tables.
        DB::table('communities')->truncate();

        foreach ($this->data() as $term) {
            $termId = $this->createTerm($term);

            if (isset($term['children'])) {
                foreach ($term['children'] as $child) {
                    $child['parent_id'] = $termId;
                    $this->createTerm($child);
                }
            }
        }
    }
}
