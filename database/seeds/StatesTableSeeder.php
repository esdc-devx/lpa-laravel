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
                    'name_fr'  => 'Nouveau',
                ],
                [
                    'name_key' => 'approved',
                    'name_en'  => 'Approved',
                    'name_fr'  => 'Approuvé',
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé',
                ],
                [
                    'name_key' => 'active',
                    'name_en'  => 'Active',
                    'name_fr'  => 'Actif',
                ],
                [
                    'name_key' => 'active-full',
                    'name_en'  => 'Active (Full)',
                    'name_fr'  => 'Actif (Complet)',
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété',
                ],
            ],
            'course' => [
                [
                    'name_key' => 'new',
                    'name_en'  => 'New',
                    'name_fr'  => 'Nouveau',
                ],
                [
                    'name_key' => 'designed',
                    'name_en'  => 'Designed',
                    'name_fr'  => 'Conçu',
                ],
                [
                    'name_key' => 'developed',
                    'name_en'  => 'Developed',
                    'name_fr'  => 'Développé',
                ],
                [
                    'name_key' => 'communications-reviewed',
                    'name_en'  => 'Communications Reviewed',
                    'name_fr'  => 'Communications Révisées',
                ],
                [
                    'name_key' => 'deployed',
                    'name_en'  => 'Deployed',
                    'name_fr'  => 'Deployé',
                ],
                [
                    'name_key' => 'published',
                    'name_en'  => 'Published',
                    'name_fr'  => 'Publié',
                ],
                [
                    'name_key' => 'end-of-life',
                    'name_en'  => 'End-of-Life',
                    'name_fr'  => 'En fin de vie',
                ],
                [
                    'name_key' => 'discontinued',
                    'name_en'  => 'Discontinued',
                    'name_fr'  => 'Discontinué',
                ],
                [
                    'name_key' => 'suspended',
                    'name_en'  => 'Suspended',
                    'name_fr'  => 'Suspendu',
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé',
                ],
                [
                    'name_key' => 'archived',
                    'name_en'  => 'Archived',
                    'name_fr'  => 'Archivé',
                ],
            ],
            'process-instance' => [
                [
                    'name_key' => 'running',
                    'name_en'  => 'Running',
                    'name_fr'  => 'En cours',
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété',
                ],
                [
                    'name_key' => 'cancelled',
                    'name_en'  => 'Cancelled',
                    'name_fr'  => 'Annulé',
                ],
            ],
            'process-step' => [
                [
                    'name_key' => 'locked',
                    'name_en'  => 'Locked',
                    'name_fr'  => 'Verrouillé',
                ],
                [
                    'name_key' => 'unlocked',
                    'name_en'  => 'Unlocked',
                    'name_fr'  => 'Déverrouillé',
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété',
                ],
                [
                    'name_key' => 'not-applicable',
                    'name_en'  => 'Not Applicable',
                    'name_fr'  => 'Sans objet',
                ],
            ],
            'process-form' => [
                [
                    'name_key' => 'locked',
                    'name_en'  => 'Locked',
                    'name_fr'  => 'Verrouillé',
                ],
                [
                    'name_key' => 'unlocked',
                    'name_en'  => 'Unlocked',
                    'name_fr'  => 'Déverrouillé',
                ],
                [
                    'name_key' => 'submitted',
                    'name_en'  => 'Submitted',
                    'name_fr'  => 'Soumis',
                ],
                [
                    'name_key' => 'rejected',
                    'name_en'  => 'Requires Adjustments',
                    'name_fr'  => 'Nécessite des ajustements',
                ],
                [
                    'name_key' => 'completed',
                    'name_en'  => 'Completed',
                    'name_fr'  => 'Complété',
                ],
                [
                    'name_key' => 'not-applicable',
                    'name_en'  => 'Not Applicable',
                    'name_fr'  => 'Sans objet',
                ],
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
