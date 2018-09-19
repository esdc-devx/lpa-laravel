<?php

namespace App\Console\Commands\User;

use App\Console\BaseCommand;
use App\Models\OrganizationalUnit;
use App\Models\User\User;
use App\Models\User\Role;

class Update extends BaseCommand
{
    protected $signature = 'user:update {username} {--role=*} {--organizational-unit=*}';
    protected $description = 'Update user roles and organizational units.';
    protected $user;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->resolveUser()) {
            $this->updateRoles();
            $this->updateOrganizationalUnits();
        }
    }

    /**
     * Resolve and validate user from username argument passed.
     *
     * @return boolean
     */
    protected function resolveUser()
    {
        if ($this->user = User::where('username', $this->argument('username'))->first()) {
            if ($this->user->username == config('auth.admin.username')) {
                return $this->error('Cannot update admin account.');
            }            
            return true;
        }
        return $this->error("Cannot find user [{$this->argument('username')}].");
    }

    /**
     * Update user roles from option passed.
     *
     * @return void
     */
    protected function updateRoles()
    {
        if ($roles = $this->option('role')) {
            $this->user->roles()->sync(
                Role::whereIn('name_key', $roles)->get()->pluck('id')
            );
        }
    }

    /**
     * Update user organizational unit from option passed.
     *
     * @return void
     */
    protected function updateOrganizationalUnits()
    {
        if ($organizationalUnits = $this->option('organizational-unit')) {
            $this->user->organizationalUnits()->sync(
                OrganizationalUnit::whereIn('name_key', $organizationalUnits)->get()->pluck('id')
            );
        }
    }
}
