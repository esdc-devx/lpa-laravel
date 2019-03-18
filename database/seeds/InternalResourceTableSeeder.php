<?php

use App\Models\Lists\InternalResource;
use Illuminate\Database\Seeder;

class InternalResourceTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_en' => 'Foundational & Specialized Learning',
                'name_fr' => 'Apprentissage de base et spécialisé',
            ],
            [
                'name_en' => 'Key Transitions, Leadership & Transformation',
                'name_fr' => 'Transitions clés, transformation et leadership',
            ],
            [
                'name_en' => 'Communications & Web Management',
                'name_fr' => 'Communications et gestion Web',
            ],
            [
                'name_en' => 'GCcampus',
                'name_fr' => 'GCcampus',
            ],
            [
                'name_en' => 'Learning Solutions',
                'name_fr' => 'Solutions d’apprentissage',
            ],
            [
                'name_en' => 'Faculty Management',
                'name_fr' => 'Gestion du personnel enseignant',
            ],
            [
                'name_en' => 'National Operational Planning',
                'name_fr' => 'Planification des opérations nationales',
            ],
            [
                'name_en' => 'Continuous Education',
                'name_fr' => 'L’apprentissage continu',
            ],
            [
                'name_en' => 'Registration & Learner Services',
                'name_fr' => 'Inscription et services à l’apprenant',
            ],
            [
                'name_en' => 'Event Management (CASE)',
                'name_fr' => 'Gestion d’évènements (CASE)',
            ],
            [
                'name_en' => 'NCR Integrated Delivery',
                'name_fr' => 'Prestation intégrée dans la RCN',
            ],
            [
                'name_en' => 'Regional Delivery',
                'name_fr' => 'Prestation régionale',
            ],
            [
                'name_en' => 'Information Technology (IT)',
                'name_fr' => 'Technologie de l’information (TI)',
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

        InternalResource::createFrom($this->data());
    }
}
