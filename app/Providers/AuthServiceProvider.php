<?php

namespace App\Providers;

use App\Models\LearningProduct\Course;
use App\Models\LearningProduct\LearningProduct;
use App\Models\OrganizationalUnit;
use App\Models\Process\ProcessInstance;
use App\Models\Process\ProcessInstanceForm;
use App\Models\Process\ProcessNotification;
use App\Models\Project\Project;
use App\Models\User\User;
use App\Policies\LearningProductPolicy;
use App\Policies\OrganizationalUnitPolicy;
use App\Policies\ProcessInstanceFormPolicy;
use App\Policies\ProcessInstancePolicy;
use App\Policies\ProcessNotificationPolicy;
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
        Course::class               => LearningProductPolicy::class,
        LearningProduct::class      => LearningProductPolicy::class,
        OrganizationalUnit::class   => OrganizationalUnitPolicy::class,
        ProcessInstance::class      => ProcessInstancePolicy::class,
        ProcessInstanceForm::class  => ProcessInstanceFormPolicy::class,
        ProcessNotification::class  => ProcessNotificationPolicy::class,
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
