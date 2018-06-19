<?php

namespace App\Providers;

use App\Models\Process\ProcessInstanceForm;
use App\Models\Project\Project;
use App\Models\User\User;
use App\Policies\ProcessInstanceFormPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ProcessInstanceForm::class  => ProcessInstanceFormPolicy::class,
        Project::class              => ProjectPolicy::class,
        User::class                 => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
