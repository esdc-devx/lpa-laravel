<?php

use Illuminate\Database\Seeder;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessStep;
use App\Models\Process\ProcessForm;
use App\Models\Process\ProcessFormAssessment;

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
    }
}
