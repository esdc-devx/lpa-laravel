<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Camunda\CamundaAuthorizations;
use App\Models\OrganizationUnit\OrganizationUnit;

class ConfigureCamunda extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'camunda:configure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Camunda application.';

    /**
     * Camunda service.
     *
     * @var App\Camunda\Camunda
     */
    protected $camunda;

    /**
     * Execute a series of actions to configure Camunda
     * on a fresh installed server.
     *
     * @return void
     */
    public function __construct()
    {
        $this->camunda = resolve('App\Camunda\Camunda');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Set app language to english since language is not defined when running
        // scripts from command line.
        app()->setLocale('en');

        if ($this->confirm('Configure groups?')) {
            $this->configureGroups();
        }

        if ($this->confirm('Configure authorization?')) {
            $this->configureAuthorizations();
        }

        if ($this->confirm('Deploy processes?')) {
            $this->deployProcesses();
        }

        $this->line('Configuration done.');
    }

    /**
     * Configure groups.
     *
     * @return void
     */
    protected function configureGroups()
    {
        // Fetch all organizational units.
        $organizationalUnits = OrganizationUnit::all();
        if ($organizationalUnits->count() === 0) {
            $this->error('No organizational units were found. You first need to seed the database.');
            return false;
        }

        // Display data in table format.
        $headers = ['id', 'name'];
        $data = [];
        foreach ($organizationalUnits as $organizationalUnit) {
            $data[] = [$organizationalUnit->unique_key, $organizationalUnit->name];
        }
        $this->table($headers, $data);

        // Proceed with groups creation.
        if ($this->confirm('This will create or replace all groups with these. Do you wish to continue?')) {
            $this->camunda->groups()->deleteAll();
            foreach ($data as $group) {
                $this->camunda->groups()->create([
                    'id'   => $group[0],
                    'name' => $group[1]
                ]);
            }

            // Create default lpa-user group.
            $this->camunda->groups()->create([
                'id'   => config('camunda.groups.user'),
                'name' => 'LPA User'
            ]);
        }

        // Update admin group default values.
        $this->camunda->groups()->update(
            config('camunda.groups.admin'),
            ['name' => 'Camunda Administrator', 'type' => 'SYSTEM']
        );

        $this->line('Groups were configured successfully.');
    }

    /**
     * Configure authorizations.
     *
     * @return void
     */
    protected function configureAuthorizations()
    {
        // Create default authorizations for user group.
        $this->camunda->authorizations()->create([
            'type'         => CamundaAuthorizations::AUTH_TYPE_GRANT,
            'resourceType' => CamundaAuthorizations::RESOURCE_PROCESS_DEFINITION,
            'permissions'  => ['READ', 'CREATE_INSTANCE'],
            'resourceId'   => '*',
            'groupId'      => config('camunda.groups.user'),
        ]);

        $this->camunda->authorizations()->create([
            'type'         => CamundaAuthorizations::AUTH_TYPE_GRANT,
            'resourceType' => CamundaAuthorizations::RESOURCE_PROCESS_INSTANCE,
            'permissions'  => ['CREATE'],
            'resourceId'   => '*',
            'groupId'      => config('camunda.groups.user'),
        ]);

        $this->line('Authorizations were configured successfully.');
    }

    /**
     * Deploy default processes.
     *
     * @return void
     */
    protected function deployProcesses()
    {
        $this->camunda->processes()->deploy([
            'name' => 'LPA Proof of Concept',
            'file' => 'LPA - POC.bpmn'
        ]);

        $this->line('Processes were deployed successfully.');
    }
}
