<?php

use App\Models\InformationSheet\InformationSheetDefinition;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessForm;
use App\Models\Process\ProcessFormAssessment;
use App\Models\Process\ProcessNotification;
use App\Models\Process\ProcessStep;
use Illuminate\Database\Seeder;

class ProcessProjectApprovalSeeder extends Seeder
{
    protected function data()
    {
        return [
            'entity_type' => 'project',
            'name_key'    => 'project-approval',
            'name_en'     => 'Project Approval',
            'name_fr'     => 'Approbation d\'un projet',
            'steps'       => [
                [
                    'name_key' => 'gate-one-approval',
                    'name_en'  => 'Gate 1 Approval',
                    'name_fr'  => 'Approbation Jalon 1',
                    'forms'    => [
                        [
                            'name_key'    => 'business-case',
                            'name_en'     => 'Business Case',
                            'name_fr'     => 'Plan d\'affaire',
                            'assessments' => [],
                        ],
                        [
                            'name_key'    => 'planned-product-list',
                            'name_en'     => 'Planned Product List',
                            'name_fr'     => 'Liste de produits prévus',
                            'assessments' => [],
                        ],
                        [
                            'name_key'    => 'gate-one-approval',
                            'name_en'     => 'Gate 1 Approval',
                            'name_fr'     => 'Approbation Jalon 1',
                            'assessments' => ['business-case', 'planned-product-list'],
                        ],
                    ],
                ],
            ],
            'notifications' => [
                [
                    'name_key'   => 'project-ready-for-gate-one-approval',
                    'name_en'    => 'Project Ready for Gate One Approval',
                    'name_fr'    => 'Projet prêt pour Jalon 1',
                ],
                [
                    'name_key'   => 'business-case-rejected',
                    'name_en'    => 'Business Case Requires Adjustments',
                    'name_fr'    => 'Plan d\'affaire nécessite des ajustements',
                ],
                [
                    'name_key'   => 'planned-product-list-rejected',
                    'name_en'    => 'Planned Product List Requires Adjustments',
                    'name_fr'    => 'Liste de produits planifiés nécessite des ajustements',
                ],
                [
                    'name_key'   => 'project-cancelled',
                    'name_en'    => 'Project Cancelled',
                    'name_fr'    => 'Projet annulé',
                ],
                [
                    'name_key'   => 'project-approved',
                    'name_en'    => 'Project Approved',
                    'name_fr'    => 'Projet approuvé',
                ],
            ],
            'information_sheets' => [
                [
                    'name_key'    => 'gate-one-project-approval',
                    'name_en'     => 'Gate 1 – Project Approval',
                    'name_fr'     => 'Jalon 1 – Approbation de projet',
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
        $data = $this->data();

        // Temporarily use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate "Project Approval" process data structure.
        $processDefinitionId = ProcessDefinition::create([
            'entity_type' => $data['entity_type'],
            'name_key'    => $data['name_key'],
            'name_en'     => $data['name_en'],
            'name_fr'     => $data['name_fr'],
        ])->id;

        // Generate process steps data.
        foreach ($data['steps'] as $key => $step) {
            $stepId = ProcessStep::create([
                'process_definition_id' => $processDefinitionId,
                'name_key'              => $step['name_key'],
                'name_en'               => $step['name_en'],
                'name_fr'               => $step['name_fr'],
                'display_sequence'      => $key,
            ])->id;

            // Generate process forms data.
            foreach ($step['forms'] as $key => $form) {
                $formId = ProcessForm::create([
                    'process_step_id'  => $stepId,
                    'name_key'         => $form['name_key'],
                    'name_en'          => $form['name_en'],
                    'name_fr'          => $form['name_fr'],
                    'display_sequence' => $key,
                ])->id;

                // Generate form assessments mapping.
                foreach ($form['assessments'] as $assessment) {
                    ProcessFormAssessment::create([
                        'process_form_id'       => $formId,
                        'assessed_process_form' => $assessment,
                    ]);
                }
            }
        }

        // Generate process notifications content.
        foreach ($data['notifications'] as $notification) {
            ProcessNotification::create([
                'process_definition_id' => $processDefinitionId,
                'name_key'              => $notification['name_key'],
                'name_en'               => $notification['name_en'],
                'name_fr'               => $notification['name_fr'],
                'subject_en'            => $notification['subject_en'] ?? $notification['name_en'],
                'subject_fr'            => $notification['subject_fr'] ?? $notification['name_fr'],
                'body_en'               => $notification['body_en'] ?? $faker->paragraph,
                'body_fr'               => $notification['body_fr'] ?? $faker->paragraph,
            ]);
        }

        // Generate process information sheet definitions.
        foreach ($data['information_sheets'] as $infosheet) {
            InformationSheetDefinition::create([
                'entity_type' => 'process-instance',
                'name_key'    => "{$data['name_key']}.{$infosheet['name_key']}",
                'name_en'     => $infosheet['name_en'],
                'name_fr'     => $infosheet['name_fr'],
            ]);
        }
    }
}
