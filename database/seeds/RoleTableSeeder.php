<?php

use Illuminate\Database\Seeder;
use App\Models\User\Role;

class RoleTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'unique_key' => 'admin',
                'name_en'    => 'Administrator',
                'name_fr'    => 'Administrateur'
            ],
            [
                'unique_key' => 'owner',
                'name_en'    => 'Content Owner',
                'name_fr'    => 'Propriétaire de contenu'
            ],
            [
                'unique_key' => 'contributor',
                'name_en'    => 'Content Contributor',
                'name_fr'    => 'Contributeur au contenu'
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
        DB::table('role_translations')->truncate();
        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();

        // Generate roles.
        foreach ($this->data() as $role) {
            Role::create([
                'unique_key' => $role['unique_key'],
                'en' => [
                    'name'    => $role['name_en'],
                ],
                'fr' => [
                    'name'    => $role['name_fr'],
                ]
            ]);
        }
    }
}
