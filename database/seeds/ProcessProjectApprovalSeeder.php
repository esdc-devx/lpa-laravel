<?php

use Illuminate\Database\Seeder;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessStep;
use App\Models\Process\ProcessForm;

class ProcessProjectApprovalSeeder extends Seeder
{
    protected function data()
    {
        return [
            'entity_type' => 'project',
            'name_key'    => 'project-approval',
            'name_en'     => 'Project Approval',
            'name_fr'     => 'Approbation de projet',
            'steps'       => [
                [
                    'name_key' => 'business-case',
                    'name_en'  => 'Business Case',
                    'name_fr'  => 'Analyse de rentabilisation',
                    'forms'    => [
                        [
                            'name_key' => 'business-case',
                            'name_en'  => 'Business Case',
                            'name_fr'  => 'Analyse de rentabilisation'
                        ],
                        [
                            'name_key' => 'business-case-assessment',
                            'name_en'  => 'Business Case Assessment',
                            'name_fr'  => 'Évaluation de l\'analyse de rentabilisation'
                        ],
                    ]
                ],
                [
                    'name_key' => 'architecture-plan',
                    'name_en'  => 'Architecture Plan',
                    'name_fr'  => 'Plan d\'architecture',
                    'forms'    => [
                        [
                            'name_key' => 'architecture-plan',
                            'name_en'  => 'Architecture Plan',
                            'name_fr'  => 'Plan d\'architecture'
                        ],
                        [
                            'name_key' => 'architecture-plan-assessment',
                            'name_en'  => 'Architecture Plan Assessment',
                            'name_fr'  => 'Évaluation du plan d\'architecture'
                        ],
                    ]
                ],
            ]
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
                ProcessForm::create([
                    'process_step_id'  => $stepId,
                    'name_key'         => $form['name_key'],
                    'name_en'          => $form['name_en'],
                    'name_fr'          => $form['name_fr'],
                    'display_sequence' => $key,
                ]);
            }
        }
    }
}
