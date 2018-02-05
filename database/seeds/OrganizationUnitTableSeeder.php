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
                'unique_key' => 'OWNER1',
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
                'unique_key' => 'OWNER2',
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
                'unique_key' => 'OWNER3',
                'name_en' => 'Leadership Development Program',
                'name_fr' => 'Programme de développement leadership',
                'acronym_en' => 'LDP',
                'acronym_fr' => 'PDL',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'OWNER4',
                'name_en' => 'Management and Professional Development',
                'name_fr' => 'Développement de profession et gestion',
                'acronym_en' => 'MDP',
                'acronym_fr' => 'DPG',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'OWNER5',
                'name_en' => 'Orientation and Authority Delegation',
                'name_fr' => 'Orientation et délégation d\'autorité',
                'acronym_en' => 'OAD',
                'acronym_fr' => 'ODA',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'OWNER6',
                'name_en' => 'Language Training',
                'name_fr' => 'Formation Linguistique',
                'acronym_en' => 'LT',
                'acronym_fr' => 'FL',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => true,
                'unique_key' => 'OWNER7',
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
                'unique_key' => 'SCIC',
                'name_en' => 'School Content Integration Committee',
                'name_fr' => 'Commité d\'intégration du contenu de l\'École',
                'acronym_en' => 'SCIC',
                'acronym_fr' => 'CICE',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'CCC',
                'name_en' => 'Client Contact Center',
                'name_fr' => 'Centre de soutien au clients',
                'acronym_en' => 'CCC',
                'acronym_fr' => 'CCC',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'GCCAMPUS',
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
                'unique_key' => 'CPNO',
                'name_en' => 'Capacity Planning National Operations',
                'name_fr' => 'Gestion de la capacité des opérations nationales',
                'acronym_en' => 'CPNO',
                'acronym_fr' => 'CPNO',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'ILMS',
                'name_en' => 'ILMS',
                'name_fr' => 'SGHA',
                'acronym_en' => 'ILMS',
                'acronym_fr' => 'SGHA',
                'email' => '',
                'director_first_name' => '',
                'director_last_name' => '',
            ],
            [
                'owner' => false,
                'unique_key' => 'LSD',
                'name_en' => 'Learning Solutions Development',
                'name_fr' => 'Développement de solutions d\'apprentissage',
                'acronym_en' => 'LSD',
                'acronym_fr' => 'LTS',
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('organization_unit_translations')->delete();
        DB::table('organization_units')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Temporarily use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate Organization Units.
        foreach ($this->data() as $organizationUnit) {
            OrganizationUnit::create([
                'owner' => $organizationUnit['owner'],
                'unique_key' => $organizationUnit['unique_key'],
                'email' => $faker->email,
                'director_first_name' => $faker->firstName,
                'director_last_name' => $faker->lastName,
                'en' => [
                    'name' => $organizationUnit['name_en'],
                    'acronym' => $organizationUnit['acronym_en'],
                ],
                'fr' => [
                    'name' => $organizationUnit['name_fr'],
                    'acronym' => $organizationUnit['acronym_fr'],
                ]
            ]);
        }
    }
}
