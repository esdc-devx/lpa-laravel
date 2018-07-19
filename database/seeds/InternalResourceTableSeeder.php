<?php

use App\Models\Project\BusinessCase\InternalResource;
use Illuminate\Database\Seeder;

class InternalResourceTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Faculty',
                'name_fr' => 'Corps professoral',
            ],
            [
                'name_en' => 'Organizational Units/SMEs',
                'name_fr' => 'Unités organisationnelles/experts',
            ],
            [
                'name_en' => 'CASE (Events)',
                'name_fr' => 'CASE (événements)',
            ],
            [
                'name_en' => 'Learning Solution Design (LSD) - Videos (Studio 2136)',
                'name_fr' => 'Conception de solutions d’apprentissage - Vidéos (Studio 2136)',
            ],
            [
                'name_en' => 'Learning Solution Design (LSD) - Other',
                'name_fr' => 'Conception de solutions d’apprentissage - Autre',
            ],
            [
                'name_en' => 'Communications and Marketing',
                'name_fr' => 'Communications et marketing',
            ],
            [
                'name_en' => 'Information Technologies (IT)',
                'name_fr' => 'Technologie de l’information (TI)',
            ],
            [
                'name_en' => 'GC Campus',
                'name_fr' => 'GC Campus',
            ],
            [
                'name_en' => 'Audio Visual - Logistic (A/V)',
                'name_fr' => 'Audiovisuel - Logistique (A/V)',
            ],
            [
                'name_en' => 'National Operations Planning (NOP)',
                'name_fr' => 'Organisation nationale de planification de la capacité (ONPC)',
            ],
            [
                'name_en' => 'Partnerships',
                'name_fr' => 'Partenariats',
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
        DB::table('internal_resources')->truncate();

        foreach ($this->data() as $term) {
            InternalResource::create([
                'name_key' => str_slug($term['name_en'], '-'),
                'name_en'  => $term['name_en'],
                'name_fr'  => $term['name_fr'],
            ]);
        }
    }
}
