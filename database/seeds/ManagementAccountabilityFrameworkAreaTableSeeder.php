<?php

use App\Models\Lists\ManagementAccountabilityFrameworkArea;
use Illuminate\Database\Seeder;

class ManagementAccountabilityFrameworkAreaTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Financial Management', 'name_fr' => 'Gestion financière', 'children' =>
                [
                    ['name_en' => 'External financial reporting', 'name_fr' => 'Établissement de rapports financiers externes'],
                    ['name_en' => 'Resource management', 'name_fr' => 'Gestion des ressources'],
                    ['name_en' => 'Internal control management', 'name_fr' => 'Gestion du contrôle interne'],
                    ['name_en' => 'Transfer payments', 'name_fr' => 'Paiements de transfert'],
                    ['name_en' => 'Stewardship of financial management systems', 'name_fr' => 'Administration des systèmes de gestion financière'],
                ],
            ],
            ['name_en' => 'Information Management and Information Technology (IM/IT) Management', 'name_fr' => 'Gestion de la Gestion de l\'information et technologie de l\'information (GI/TI)', 'children' =>
                [
                    ['name_en' => 'IM stewardship', 'name_fr' => 'Administration de la GI'],
                    ['name_en' => 'IM program / service enablement', 'name_fr' => 'Activation des programmes ou services de la GI'],
                    ['name_en' => 'IT stewardship', 'name_fr' => 'Administration de la TI'],
                    ['name_en' => 'IT program / service enablement', 'name_fr' => 'Activation des programmes ou services de la TI'],
                    ['name_en' => 'Enterprise priorities alignment', 'name_fr' => 'Harmonisation avec les priorités d’entreprise'],
                    ['name_en' => 'IM/IT leadership and workforce capacity', 'name_fr' => 'Leadership de la GI/TI et capacité de l’effectif'],
                    ['name_en' => 'Service performance', 'name_fr' => 'Rendement du service'],
                ],
            ],
            ['name_en' => 'Management of Acquired Services and Assets', 'name_fr' => 'Gestions des services et des actifs acquis', 'children' =>
                [
                    ['name_en' => 'Investment planning and management of projects', 'name_fr' => 'Planification des investissements et gestion des projets'],
                    ['name_en' => 'Procurement', 'name_fr' => 'Approvisionnement'],
                    ['name_en' => 'Real property and materiel', 'name_fr' => 'Biens immobiliers et matériels'],
                    ['name_en' => 'Procurement, materiel management and real property communities', 'name_fr' => 'Collectivités des acquisitions, de la gestion du matériel et des biens immobiliers'],
                ],
            ],
            ['name_en' => 'Management of Integrated Risk, Planning and Performance', 'name_fr' => 'Gestion intégrée des risques, de la planification et du rendement', 'children' =>
                [
                    ['name_en' => 'Creation of quality performance information', 'name_fr' => 'Création d’information sur la qualité du rendement'],
                    ['name_en' => 'Use of performance information in decision-making', 'name_fr' => 'Utilisation de l’information sur le rendement dans les prises de décision'],
                    ['name_en' => 'Internal and External Performance Reporting', 'name_fr' => 'Production de rapports internes et externes sur le rendement'],
                ],
            ],
            ['name_en' => 'People Management', 'name_fr' => 'Gestion des personnes', 'children' =>
                [
                    ['name_en' => 'Workforce, work culture and workplace', 'name_fr' => 'Effectif, culture de travail et milieu de travail'],
                    ['name_en' => 'Performance management, employee learning, and development', 'name_fr' => 'Gestion du rendement, apprentissage et perfectionnement des employés'],
                    ['name_en' => 'Service standards', 'name_fr' => 'Normes de service'],
                ],
            ],
            ['name_en' => 'Security Management', 'name_fr' => 'Gestion de la sécurité', 'children' =>
                [
                    ['name_en' => 'Effective and integrated department or agency security management', 'name_fr' => 'Gestion efficace et intégrée de la sécurité du ministère ou de l’organisme'],
                    ['name_en' => 'Protection of Government of Canada assets and continued program/service delivery', 'name_fr' => 'Protection des actifs du gouvernement du Canada et prestation continue des programmes et services'],
                    ['name_en' => 'Government of Canada security policy priority alignment', 'name_fr' => 'Harmonisation des priorités de la Politique sur la sécurité du gouvernement du Canada'],
                ],
            ],
            ['name_en' => 'Service Management', 'name_fr' => 'Gestion du service', 'children' =>
                [
                    ['name_en' => 'Service stewardship', 'name_fr' => 'Administration du service'],
                    ['name_en' => 'Enterprise alignment', 'name_fr' => 'Harmonisation avec l’entreprise'],
                    ['name_en' => 'Client-centric service', 'name_fr' => 'Service axé sur la clientèle'],
                ],
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
        DB::table('design_management_accountability_framework_area')->truncate();
        DB::table('management_accountability_framework_areas')->truncate();

        ManagementAccountabilityFrameworkArea::createFrom($this->data());
    }
}
