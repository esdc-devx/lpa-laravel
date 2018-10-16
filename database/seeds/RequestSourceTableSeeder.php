<?php

use App\Models\Project\BusinessCase\RequestSource;
use Illuminate\Database\Seeder;

class RequestSourceTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Change in Policy',
                'name_fr' => 'Changement de la politique',
            ],
            [
                'name_en' => 'Lifecycle Plan',
                'name_fr' => 'Gestion du cycle de vie des produits',
            ],
            [
                'name_en' => 'Central Agency Request',
                'name_fr' => 'Demande des organismes centraux',
            ],
            [
                'name_en' => 'Emerging Priority',
                'name_fr' => 'Priorité émergente',
            ],
            [
                'name_en' => 'Technical Issue',
                'name_fr' => 'Problème technique',
            ],
            [
                'name_en' => 'Compliance Issue',
                'name_fr' => 'Enjeu de conformité',
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
