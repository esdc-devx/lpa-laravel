<?php

use App\Models\Project\BusinessCase\RiskType;
use Illuminate\Database\Seeder;

class RiskTypeTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Budget',
                'name_fr' => 'Budget',
            ],
            [
                'name_en' => 'Buy-in',
                'name_fr' => 'Appui',
            ],

            [
                'name_en' => 'Change in business processes',
                'name_fr' => 'Modification des processus d’affaires',
            ],
            [
                'name_en' => 'Human resources/skills needed',
                'name_fr' => 'Ressources humaines/compétences requises',
            ],
            [
                'name_en' => 'Scope',
                'name_fr' => 'Portée',
            ],
            [
                'name_en' => 'Security/Firewalls',
                'name_fr' => 'Sécurité/pare-feu',
            ],
            [
                'name_en' => 'Technical complexity',
                'name_fr' => 'Complexité technique',
            ],
            [
                'name_en' => 'Time constraints',
                'name_fr' => 'Contraintes de temps',
            ],
            [
                'name_en' => 'Technical risk',
                'name_fr' => 'Risque technique',
            ],
            [
                'name_en' => 'Unclear roles and responsibilities with partners',
                'name_fr' => 'Manque de clarté des rôles et des responsabilités avec les partenaires',
            ],
            [
                'name_en' => 'Unstable content',
                'name_fr' => 'Contenu instable',
            ],
            [
                'name_en' => 'Various impacts on product quality or CSPS reputation',
                'name_fr' => 'Impacts divers sur la qualité du produit ou la réputation de l’EFPC',
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
        DB::table('risk_types')->truncate();

        foreach ($this->data() as $term) {
            RiskType::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
