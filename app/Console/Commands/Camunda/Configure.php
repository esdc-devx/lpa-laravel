<?php

namespace App\Console\Commands\Camunda;

use App\Camunda\APIs\CamundaAuthorizations;
use App\Camunda\Exceptions\ResourceNotFoundException;
use App\Console\BaseCommand;
use App\Models\OrganizationalUnit;
use App\Models\User\User;

class Configure extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'camunda:configure {--force}';

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
        if ($this->option('force') || $this->confirm('Configure groups?')) {
            $this->configureGroups();
        }

        if ($this->option('force') || $this->confirm('Configure authorization?')) {
            $this->configureAuthorizations();
        }

        $this->success('Camunda configuration completed successfully.');
    }

    /**
     * Configure groups.
     *
     * @return void
     */
    protected function configureGroups()
    {
        // Fetch all organizational units.
        $organizationalUnits = OrganizationalUnit::all();
        if ($organizationalUnits->count() === 0) {
            $this->error('No organizational units were found. You first need to seed the database.');
            return false;
        }

        // Display data in table format.
        $headers = ['id', 'name'];
        $data = [];
        foreach ($organizationalUnits as $organizationalUnit) {
            $data[] = [$organizationalUnit->name_key, $organizationalUnit->name];
        }
        $this->table($headers, $data);

        // Proceed with groups creation.
        try {
            if ($this->option('force') || $this->confirm('This will create or replace all groups with these. Do you wish to continue?')) {
                $this->camunda->groups()->deleteAll();
                foreach ($data as $group) {
                    $this->camunda->groups()->create([
                        'id'   => $group[0],
                        'name' => $group[1]
                    ]);
                }

                // Create default lpa-user group.
                $this->camunda->groups()->create([
                    'id'   => $this->camunda->config('app.groups.user'),
                    'name' => 'LPA User'
                ]);

                // Add existing users to default lpa-user group.
                User::where('username', '!=', config('auth.admin.username'))->get()
                    ->each(function($user) {
                        try {
                            $this->camunda->users()->get($user);
                        }
                        // Create user if they don't exist in Camunda.
                        catch (ResourceNotFoundException $e) {
                            $this->camunda->users()->create($user);
                        }
                        $this->camunda->groups()->add($user, $this->camunda->config('app.groups.user'));
                    });
            }

            // Update admin group default values.
            $this->camunda->groups()->update(
                $this->camunda->config('app.groups.admin'),
                ['name' => 'Camunda Administrator', 'type' => 'SYSTEM']
            );

            $this->line('Groups were configured successfully.');
        }
        // Catch and output any possible exceptions.
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Configure authorizations.
     *
     * @return void
     */
    protected function configureAuthorizations()
    {
        // Create default authorizations for user group.
        try {
            $this->camunda->authorizations()->create([
                'type'         => CamundaAuthorizations::AUTH_TYPE_GRANT,
                'resourceType' => CamundaAuthorizations::RESOURCE_PROCESS_DEFINITION,
                'permissions'  => ['READ', 'CREATE_INSTANCE'],
                'resourceId'   => '*',
                'groupId'      => $this->camunda->config('app.groups.user'),
            ]);

            $this->camunda->authorizations()->create([
                'type'         => CamundaAuthorizations::AUTH_TYPE_GRANT,
                'resourceType' => CamundaAuthorizations::RESOURCE_PROCESS_INSTANCE,
                'permissions'  => ['CREATE'],
                'resourceId'   => '*',
                'groupId'      => $this->camunda->config('app.groups.user'),
            ]);
            $this->line('Authorizations were configured successfully.');
        }
        // Catch and output any possible exceptions.
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
