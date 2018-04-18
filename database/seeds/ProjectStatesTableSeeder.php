<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectState;

class ProjectStatesTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            [
                'unique_key' => 'new',
                'name_en'    => 'New',
                'name_fr'    => 'Nouveau'
            ],
            [
                'unique_key' => 'defined',
                'name_en'    => 'Defined',
                'name_fr'    => 'Défini'
            ],
            [
                'unique_key' => 'rejected',
                'name_en'    => 'Rejected',
                'name_fr'    => 'Rejeté'
            ],
            [
                'unique_key' => 'approved',
                'name_en' => 'Approved',
                'name_fr' => 'Approuvé'
            ],
            [
                'unique_key' => 'cancelled',
                'name_en' => 'Cancelled',
                'name_fr' => 'Annulé'
            ],
            [
                'unique_key' => 'active',
                'name_en' => 'Active',
                'name_fr' => 'Actif'
            ],
            [
                'unique_key' => 'active-full',
                'name_en' => 'Active (Full)',
                'name_fr' => 'Actif (Complet)'
            ],
            [
                'unique_key' => 'completed',
                'name_en' => 'Completed',
                'name_fr' => 'Complété'
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
        DB::table('project_state_translations')->truncate();
        DB::table('project_states')->truncate();

        // Generate project states.
        foreach ($this->data() as $projectState) {
            ProjectState::create([
                'unique_key'  => $projectState['unique_key'],
                'en' => [
                    'name'    => $projectState['name_en'],
                ],
                'fr' => [
                    'name'    => $projectState['name_fr'],
                ]
            ]);
        }
    }
}
