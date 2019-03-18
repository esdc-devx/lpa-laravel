<?php

use App\Models\Lists\Series;
use Illuminate\Database\Seeder;

class SeriesTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            ['name_en' => 'Briefing Series', 'name_fr' => 'Série breffage'],
            ['name_en' => 'Essentials Series', 'name_fr' => 'Série des essentiels', 'children' =>
                [
                    ['name_en' => 'Essentials of Access to Information and Privacy', 'name_fr' => 'Essentiels de l’accès à l’information et protection des renseignements personnels'],
                    ['name_en' => 'Essentials of Communications', 'name_fr' => 'Essentiels des communications'],
                    ['name_en' => 'Essentials of Finance', 'name_fr' => 'Essentiels des finances'],
                    ['name_en' => 'Essentials of Human Resources', 'name_fr' => 'Essentiels des ressources humaines'],
                    ['name_en' => 'Essentials of Information Management', 'name_fr' => 'Essentiels de la gestion de l’information'],
                    ['name_en' => 'Essentials of Information Technology', 'name_fr' => 'Essentiels de la technologie de l’information'],
                ],
            ],
            ['name_en' => 'GCdocs', 'name_fr' => 'GCdocs'],
            ['name_en' => 'GCTools', 'name_fr' => 'OutilsGC'],
            ['name_en' => 'Gender Based Analysis Plus (GBA+)', 'name_fr' => 'L’analyse comparative entre les sexes plus (ACS+)'],
            ['name_en' => 'Indigenous Learning Series', 'name_fr' => 'Série d’apprentissage sur les questions autochtones'],
            ['name_en' => 'Language Maintenance Tools', 'name_fr' => 'Outils de maintien des acquis linguistiques'],
            ['name_en' => 'Open Government', 'name_fr' => 'Gouvernement ouvert'],
            ['name_en' => 'Official Languages For New Public Servants', 'name_fr' => 'Les langues officielles pour les nouveaux fonctionnaires'],
            ['name_en' => 'Performance Management Cycle', 'name_fr' => 'Cycle de gestion du rendement'],
            ['name_en' => 'Project Management Series', 'name_fr' => 'Série Gestion de projet'],
            ['name_en' => 'Public Service Orientation', 'name_fr' => 'Orientation à la fonction publique'],
            ['name_en' => 'Results and Delivery', 'name_fr' => 'Résultats et livraison'],
            ['name_en' => 'Transformation in the Public Service', 'name_fr' => 'La transformation au sein de la fonction publique'],

        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('design_series')->truncate();
        DB::table('series')->truncate();

        Series::createFrom($this->data());
    }
}
