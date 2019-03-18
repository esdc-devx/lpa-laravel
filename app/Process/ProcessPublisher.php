<?php

namespace App\Process;

use App\Events\ProcessDeployed;
use App\Models\InformationSheet\InformationSheetDefinition;
use App\Models\Process\ProcessDefinition;
use App\Models\Process\ProcessDefinitionEntityType;
use App\Models\Process\ProcessForm;
use App\Models\Process\ProcessFormAssessment;
use App\Models\Process\ProcessNotification;
use App\Models\Process\ProcessStep;
use App\Support\ConsoleOutput;

class ProcessPublisher
{
    protected $blueprint;
    protected $processDefinition;

    public function __construct(ConsoleOutput $output) {
        $this->output = $output;
    }

    /**
     * Load process definition blueprint class from key.
     * i.e. project-approval => App\Models\Process\Blueprints\ProjectApproval.
     *
     * @param  string $processDefinitionKey
     * @return $this
     */
    public function load($processDefinitionKey) {
        $this->blueprint = ProcessDefinition::getBlueprintFor($processDefinitionKey);
        return $this;
    }

    /**
     * Deploy a process using a process definition blueprint.
     * This will generate all required data for a process to display and work.
     *
     * @return $this
     */
    public function deploy()
    {
        // Ensure a blueprint was previously loaded.
        if (! $this->blueprint) {
            throw new \Exception('You must first load a process definition.');
        }

        $definitionKey = $this->getDefinition()['name_key'];
        $this->output->info("Deploying [{$definitionKey}] process...");

        // Create process definition.
        $this->createDefinition();

        // Create process structure (steps, forms and assessments).
        $this->createStructure();

        // Create process notifications content (emails sent during process progression).
        $this->createNotifications();

        // Create information sheets definition associated with the process.
        $this->createInformationSheetsDefinition();

        // Fire a ProcessDeployed event to let CamundaEventSubscriber deploy its process.
        event(new ProcessDeployed($this->processDefinition));

        $this->output->success('Process deployed successfully.');

        return $this;
    }

    /**
     * Get process definition data from blueprint.
     *
     * @return array
     */
    protected function getDefinition()
    {
        $definition = $this->blueprint->getDefinition();
        $definition['name_key'] = $definition['name_key'] ?? kebab_case($definition['name_en']);
        return $definition;
    }

    /**
     * Generate process definition.
     *
     * @return ProcessDefinition
     */
    protected function createDefinition()
    {
        // Create process definition.
        $definition = $this->getDefinition();
        $this->processDefinition = ProcessDefinition::create($definition);

        // Create mapping for all supported entity types.
        // i.e. Which entity types can launch this process.
        collect($definition['entity_types'])->each(function ($entityType) {
            ProcessDefinitionEntityType::create([
                'process_definition_id' => $this->processDefinition->id,
                'entity_type'           => $entityType,
            ]);
        });

        return $this->processDefinition;
    }

    /**
     * Generate process structure (steps, forms and assessments).
     *
     * @return void
     */
    protected function createStructure()
    {
        $steps = $this->blueprint->getStructure();
        foreach ($steps as $index => $step) {
            // Generate process steps.
            $processStep = ProcessStep::create(
                array_merge($step, [
                    'name_key'              => $step['name_key'] = $step['name_key'] ?? kebab_case($step['name_en']),
                    'process_definition_id' => $this->processDefinition->id,
                    'display_sequence'      => $index,
                ])
            );

            foreach ($step['forms'] as $index => $form) {
                // Generate process forms under each steps.
                $processForm = ProcessForm::create(
                    array_merge($form, [
                        'name_key'         => $form['name_key'] ?? kebab_case($form['name_en']),
                        'process_step_id'  => $processStep->id,
                        'display_sequence' => $index,
                    ])
                );

                if (! empty($form['assessments'])) {
                    // Generate form assessments mapping if any.
                    foreach ($form['assessments'] as $assessment) {
                        ProcessFormAssessment::create([
                            'process_form_id'       => $processForm->id,
                            'assessed_process_form' => $assessment,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Generate process notifications content (emails being sent during process progression).
     *
     * @return void
     */
    protected function createNotifications()
    {
        $notifications = $this->blueprint->getNotifications();

        // Use Faker to generate some fake data.
        $faker = \Faker\Factory::create();

        // Generate process notifications content.
        foreach ($notifications as $notification) {
            ProcessNotification::create([
                'process_definition_id' => $this->processDefinition->id,
                'name_key'              => $notification['name_key'] ?? kebab_case($notification['name_en']),
                'name_en'               => $notification['name_en'],
                'name_fr'               => $notification['name_fr'],
                'subject_en'            => $notification['subject_en'] ?? $notification['name_en'],
                'subject_fr'            => $notification['subject_fr'] ?? $notification['name_fr'],
                'body_en'               => $notification['body_en'] ?? $faker->paragraph,
                'body_fr'               => $notification['body_fr'] ?? $faker->paragraph,
                'created_by'            => auth()->user()->id,
                'updated_by'            => auth()->user()->id,
            ]);
        }
    }

    /**
     * Generate information sheets associated with the process.
     *
     * @return void
     */
    protected function createInformationSheetsDefinition()
    {
        $informationSheets = $this->blueprint->getInformationSheets();

        foreach ($informationSheets as $infosheet) {
            $infosheet['name_key'] = $infosheet['name_key'] ?? kebab_case($infosheet['name_en']);
            InformationSheetDefinition::create([
                'entity_type' => 'process-instance',
                'name_key'    => "{$this->processDefinition->name_key}.{$infosheet['name_key']}",
                'name_en'     => $infosheet['name_en'],
                'name_fr'     => $infosheet['name_fr'],
            ]);
        }
    }

}
