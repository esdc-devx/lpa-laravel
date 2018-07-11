<?php

use Illuminate\Database\Seeder;
use App\Models\State;

class StatesTableSeeder extends Seeder
{
    protected function data()
    {
        return [
            'project' => [
                [
                    'name_key' => 'new',
                    'name_en'  => 'New',
                    'name_fr'  => 'Nouveau'
                ],
                [
                    'name_key' => 'defined',
                    'name_en'  => 'Defined',
                    'name_fr'  => 'Défini'
                ],
                [
                    'name_key' => 'approved',
                    'name_en'  => 'Approved',
                    'name_fr'  => 'Approuvé'
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé'
                ],
                [
                    'name_key' => 'active',
                    'name_en'  => 'Active',
                    'name_fr'  => 'Actif'
                ],
                [
                    'name_key' => 'active-full',
                    'name_en'  => 'Active (Full)',
                    'name_fr'  => 'Actif (Complet)'
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété'
                ],
            ],
            'process-instance' => [
                [
                    'name_key' => 'active',
                    'name_en'  => 'Running',
                    'name_fr'  => 'En cours'
                ],
                [
                    'name_key' => 'suspended',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé'
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété'
                ],
                [
                    'name_key' => 'externally-terminated',
                    'name_en'  => 'Terminated',
                    'name_fr'  => 'Terminé'
                ],
                [
                    'name_key' => 'internally-terminated',
                    'name_en'  => 'Terminated',
                    'name_fr'  => 'Terminé'
                ],
            ],
            'process-step' => [
                [
                    'name_key' => 'locked',
                    'name_en'  => 'Locked',
                    'name_fr'  => 'Verrouillé'
                ],
                [
                    'name_key' => 'unlocked',
                    'name_en'  => 'Unlocked',
                    'name_fr'  => 'Déverrouillé'
                ],
                [
                    'name_key' => 'done',
                    'name_en'  => 'Done',
                    'name_fr'  => 'Complété'
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé'
                ]
            ],
            'process-form' => [
                [
                    'name_key' => 'locked',
                    'name_en'  => 'Locked',
                    'name_fr'  => 'Verrouillé'
                ],
                [
                    'name_key' => 'unlocked',
                    'name_en'  => 'Unlocked',
                    'name_fr'  => 'Déverrouillé'
                ],
                [
                    'name_key' => 'submitted',
                    'name_en'  => 'Submitted',
                    'name_fr'  => 'Soumis'
                ],
                [
                    'name_key' => 'rejected',
                    'name_en'  => 'Rejected',
                    'name_fr'  => 'Rejeté'
                ],
                [
                    'name_key' => 'done',
                    'name_en'  => 'Done',
                    'name_fr'  => 'Complété'
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé'
                ]
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
        DB::table('states')->truncate();

        // Generate all entities states.
        foreach ($this->data() as $entity => $states) {
            foreach ($states as $state) {
                State::create(array_merge([
                    'entity_type' => $entity
                ], $state));
            }
        }
    }
}
