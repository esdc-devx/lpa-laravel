<?php

use App\Models\OrganizationalUnit\OrganizationalUnit;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;

class OrganizationalUnitTableSeeder extends Seeder
{
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

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
                'director_username' => 'JRICHERG',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-2',
                'name_en' => 'Functional Community 2',
                'name_fr' => 'Communauté fonctionelle 2',
                'acronym_en' => 'FC2',
                'acronym_fr' => 'CF2',
                'email' => '',
                'director_username' => 'JRICHERG',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-3',
                'name_en' => 'Leadership Development Program',
                'name_fr' => 'Programme de développement de leadership',
                'acronym_en' => 'LDP',
                'acronym_fr' => 'PDL',
                'email' => '',
                'director_username' => 'CVANDALE',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-4',
                'name_en' => 'Management and Professional Development',
                'name_fr' => 'Perfectionnement en gestion et professionnel',
                'acronym_en' => 'MDP',
                'acronym_fr' => 'DPG',
                'email' => 'csps.managementdevelopmentprogram-programmedeperfectionnementdesgestionnaires.efpc@canada.ca',
                'director_username' => 'CVANDALE',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-5',
                'name_en' => 'Orientation and Authority Delegation',
                'name_fr' => 'Orientation et délégation des pouvoirs',
                'acronym_en' => 'OAD',
                'acronym_fr' => 'ODA',
                'email' => 'csps.orientation.efpc@canada.ca',
                'director_username' => 'JRICHERG',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-6',
                'name_en' => 'Language Training',
                'name_fr' => 'Formation linguistique',
                'acronym_en' => 'LT',
                'acronym_fr' => 'FL',
                'email' => 'csps.languagelearningapprentissagelinguistique.efpc@canada.ca',
                'director_username' => 'FMAWN',
            ],
            [
                'owner' => true,
                'unique_key' => 'owner-7',
                'name_en' => 'Transformation',
                'name_fr' => 'Transformation',
                'acronym_en' => 'TRANSF',
                'acronym_fr' => 'TRANSF',
                'email' => 'csps.transformation.efpc@canada.ca',
                'director_username' => 'RLANDRY',
            ],
            [
                'owner' => false,
                'unique_key' => 'scic',
                'name_en' => 'School Content Integration Committee',
                'name_fr' => 'Comité d’intégration du contenu de l’école',
                'acronym_en' => 'SCIC',
                'acronym_fr' => 'CICE',
                'email' => 'csps.scic.cice.efpc@canada.ca',
                'director_username' => 'LNOWOSIE',
            ],
            [
                'owner' => false,
                'unique_key' => 'client-services',
                'name_en' => 'Clients Services',
                'name_fr' => 'Service aux clients',
                'acronym_en' => 'CCC',
                'acronym_fr' => 'CCC',
                'email' => 'csps.clientservices-servicesclients.efpc@canada.ca',
                'director_username' => 'LMACMILL',
            ],
            [
                'owner' => false,
                'unique_key' => 'gccampus',
                'name_en' => 'GCcampus',
                'name_fr' => 'GCcampus',
                'acronym_en' => 'GCCAMPUS',
                'acronym_fr' => 'GCCAMPUS',
                'email' => 'csps.gccampus.efpc@canada.ca',
                'director_username' => 'DHILZ',
            ],
            [
                'owner' => false,
                'unique_key' => 'ilms',
                'name_en' => 'Integrated Learning Management System',
                'name_fr' => 'Système harmonisé de gestion de l’apprentissage',
                'acronym_en' => 'ILMS',
                'acronym_fr' => 'SHGA',
                'email' => 'csps.ilmsbusinessoperationsoperationsdaffaireshga.efpc@canada.ca',
                'director_username' => 'DHILZ',
            ],
            [
                'owner' => false,
                'unique_key' => 'nop',
                'name_en' => 'National Operations Planning',
                'name_fr' => 'Planification des opérations nationales',
                'acronym_en' => 'NOP',
                'acronym_fr' => 'NOP',
                'email' => 'csps.capacityplanningplanificationdelacapacite.efpc@canada.ca',
                'director_username' => 'LCYR',
            ],
            [
                'owner' => false,
                'unique_key' => 'lsd',
                'name_en' => 'Learning Solution Division',
                'name_fr' => 'Division des solutions d\'apprentissage',
                'acronym_en' => 'LSD',
                'acronym_fr' => 'DSA',
                'email' => 'csps.learningsolutionsrequest-demandesolutionsapprentissage.efpc@canada.ca',
                'director_username' => 'LNOWOSIE',
            ],
            [
                'owner' => false,
                'unique_key' => 'comms',
                'name_en' => 'Communications, Marketing and Web Management',
                'name_fr' => 'Communications, marketing et gestion du Web',
                'acronym_en' => 'COMMS',
                'acronym_fr' => 'COMMS',
                'email' => 'csps.communications.efpc@canada.ca',
                'director_username' => 'SSTJULIE',
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
        DB::table('organizational_unit_translations')->truncate();
        DB::table('organizational_unit_user')->truncate();
        DB::table('organizational_units')->truncate();

        // Temporarily use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate Organization Units.
        foreach ($this->data() as $organizationalUnit) {
            // Get or create user to be set as director for organizational unit.
            try {
                $director = $this->users->getItemByColumn($organizationalUnit['director_username'], 'username');
            }
            // If user does not exist, create it.
            catch (ModelNotFoundException $e) {
                $director = $this->users->create([
                    'username' => $organizationalUnit['director_username']
                ]);
            }

            OrganizationalUnit::create([
                'owner'               => $organizationalUnit['owner'],
                'unique_key'          => $organizationalUnit['unique_key'],
                'email'               => $faker->email,
                'director'            => $director->id,
                'en'                  => [
                    'name'    => $organizationalUnit['name_en'],
                    'acronym' => $organizationalUnit['acronym_en'],
                ],
                'fr'                  => [
                    'name'    => $organizationalUnit['name_fr'],
                    'acronym' => $organizationalUnit['acronym_fr'],
                ]
            ]);
        }
    }
}
