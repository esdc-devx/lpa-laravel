<?php

use Illuminate\Database\Seeder;
use App\Models\User\Role;

class RoleTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'name_key' => 'admin',
                'name_en'  => 'Administrator',
                'name_fr'  => 'Administrateur'
            ],
            [
                'name_key' => 'owner',
                'name_en'  => 'Learning Product Owner',
                'name_fr'  => 'Propriétaire de produits d\'apprentissage'
            ],
            [
                'name_key' => 'process-contributor',
                'name_en'  => 'Learning Product Contributor',
                'name_fr'  => 'Contributeur aux produits d\'apprentissage'
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
        DB::table('role_user')->truncate();
        DB::table('roles')->truncate();

        Role::createFrom($this->data());
    }
}
