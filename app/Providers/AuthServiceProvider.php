<?php

namespace App\Providers;

use App\Models\LearningProduct\LearningProduct;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Project\Project;
use App\Models\User\User;
use App\Policies\LearningProductPolicy;
use App\Policies\ProcessInstanceFormPolicy;
use App\Policies\ProcessInstancePolicy;
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
        LearningProduct::class      => LearningProductPolicy::class,
        ProcessInstanceForm::class  => ProcessInstanceFormPolicy::class,
        ProcessInstance::class      => ProcessInstancePolicy::class,
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