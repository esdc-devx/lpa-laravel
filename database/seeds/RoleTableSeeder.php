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
                'name_en'  => 'Content Owner',
                'name_fr'  => 'Propriétaire de contenu'
            ],
            [
                'name_key' => 'process-contributor',
                'name_en'  => 'Process Contributor',
                'name_fr'  => 'Contributeur aux processus'
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

        // Generate roles.
        foreach ($this->data() as $role) {
            Role::create($role);
        }
    }
}
