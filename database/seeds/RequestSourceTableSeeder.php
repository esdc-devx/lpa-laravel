<?php

use App\Models\Project\BusinessCase\RequestSource;
use Illuminate\Database\Seeder;

class RequestSourceTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Product Evergreening',
                'name_fr' => 'Mise à jour en continu du produit',
            ],
            [
                'name_en' => 'Organization Unit Initiative',
                'name_fr' => 'Initiative de l’unité organisationnelle',
            ],
            [
                'name_en' => 'Technical Issue',
                'name_fr' => 'Problème technique',
            ],
            [
                'name_en' => 'Change in Policy',
                'name_fr' => 'Changement de politique',
            ],
            [
                'name_en' => 'OCHRO or PCO request',
                'name_fr' => 'Demande du BDPRH ou du BCP',
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
        DB::table('request_sources')->truncate();
        DB::table('business_case_request_source')->truncate();

        foreach ($this->data() as $term) {
            RequestSource::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
