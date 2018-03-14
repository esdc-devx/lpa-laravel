<?php

use Illuminate\Database\Seeder;
use App\Models\OrganizationUnit\OrganizationUnit;

class OrganizationUnitTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'owner' => true,
                'unique_key' => 'owner-1',
                'name_en' => 'Functional Community 1',
                'name_fr' => 'Communauté fonctionelle 1',
                'acronym_en' => 'FC1',
                'acronym_fr' => 'CF1',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-2',
                'name_en' => 'Functional Community 2',
                'name_fr' => 'Communauté fonctionelle 2',
                'acronym_en' => 'FC2',
                'acronym_fr' => 'CF2',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-3',
                'name_en' => 'Leadership Development Program',
                'name_fr' => 'Programme de perfectionnement en leadership',
                'acronym_en' => 'LDP',
                'acronym_fr' => 'PDL',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-4',
                'name_en' => 'Management and Professional Development',
                'name_fr' => 'Perfectionnement en gestion et professionnel',
                'acronym_en' => 'MDP',
                'acronym_fr' => 'DPG',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-5',
                'name_en' => 'Orientation and Authority Delegation',
                'name_fr' => 'Orientation et délégation des pouvoirs',
                'acronym_en' => 'OAD',
                'acronym_fr' => 'ODA',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-6',
                'name_en' => 'Language Training',
                'name_fr' => 'Formation linguistique',
                'acronym_en' => 'LT',
                'acronym_fr' => 'FL',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-7',
                'name_en' => 'Transformation',
                'name_fr' => 'Transformation',
                'acronym_en' => 'TRANSF',
                'acronym_fr' => 'TRANSF',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'scic',
                'name_en' => 'School Content Integration Committee',
                'name_fr' => 'Comité d’intégration du contenu de l’école',
                'acronym_en' => 'SCIC',
                'acronym_fr' => 'CICE',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'client-services',
                'name_en' => 'Client Services',
                'name_fr' => 'Services aux clients',
                'acronym_en' => 'CCC',
                'acronym_fr' => 'CCC',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'gccampus',
                'name_en' => 'GCcampus',
                'name_fr' => 'GCcampus',
                'acronym_en' => 'GCCAMPUS',
                'acronym_fr' => 'GCCAMPUS',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'ilms',
                'name_en' => 'ILMS Business Operations',
                'name_fr' => 'Operations d’affaires SHGA',
                'acronym_en' => 'ILMS',
                'acronym_fr' => 'SGHA',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'nop',
                'name_en' => 'National Operations Planning',
                'name_fr' => 'Planification des opérations national',
                'acronym_en' => 'NOP',
                'acronym_fr' => 'NOP',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'lsd',
                'name_en' => 'Learning Solution Division',
                'name_fr' => 'Division des solutions apprentissage',
                'acronym_en' => 'LSD',
                'acronym_fr' => 'DSA',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'comms',
                'name_en' => 'Communications, Marketing & Web Content Management',
                'name_fr' => 'Communications, marketing  et gestion du Web',
                'acronym_en' => 'COMMS',
                'acronym_fr' => 'COMMS',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
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
        DB::table('organization_unit_translations')->truncate();
        DB::table('organization_unit_user')->truncate();
        DB::table('organization_units')->truncate();

        // Temporarily use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate Organization Units.
        foreach ($this->data() as $organizationUnit) {
            OrganizationUnit::create([
                'owner'               => $organizationUnit['owner'],
                'unique_key'          => $organizationUnit['unique_key'],
                'email'               => $faker->email,
                'director_first_name' => $faker->firstName,
                'director_last_name'  => $faker->lastName,
                'en'                  => [
                    'name'    => $organizationUnit['name_en'],
                    'acronym' => $organizationUnit['acronym_en'],
                ],
                'fr'                  => [
                    'name'    => $organizationUnit['name_fr'],
                    'acronym' => $organizationUnit['acronym_fr'],
                ]
            ]);
        }
    }
}
