<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services your application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'America/Toronto',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',
    'supported_locales' => ['en', 'fr'],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Mailbox Configuration
    |--------------------------------------------------------------------------
    |
    | Application mailbox to which error notifications get sent, etc.
    |
     */

    'mailbox' => env('MAIL_BOX', ''),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\DatabaseServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Adldap\Laravel\AdldapServiceProvider::class,
        Adldap\Laravel\AdldapAuthServiceProvider::class,
        App\Camunda\CamundaServiceProvider::class,
        App\Process\ProcessServiceProvider::class,
        Nestable\NestableServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        'App'          => Illuminate\Support\Facades\App::class,
        'Artisan'      => Illuminate\Support\Facades\Artisan::class,
        'Auth'         => Illuminate\Support\Facades\Auth::class,
        'Blade'        => Illuminate\Support\Facades\Blade::class,
        'Broadcast'    => Illuminate\Support\Facades\Broadcast::class,
        'Bus'          => Illuminate\Support\Facades\Bus::class,
        'Cache'        => Illuminate\Support\Facades\Cache::class,
        'Config'       => Illuminate\Support\Facades\Config::class,
        'Cookie'       => Illuminate\Support\Facades\Cookie::class,
        'Crypt'        => Illuminate\Support\Facades\Crypt::class,
        'DB'           => Illuminate\Support\Facades\DB::class,
        'Eloquent'     => Illuminate\Database\Eloquent\Model::class,
        'Event'        => Illuminate\Support\Facades\Event::class,
        'File'         => Illuminate\Support\Facades\File::class,
        'Gate'         => Illuminate\Support\Facades\Gate::class,
        'Hash'         => Illuminate\Support\Facades\Hash::class,
        'Lang'         => Illuminate\Support\Facades\Lang::class,
        'Log'          => Illuminate\Support\Facades\Log::class,
        'Mail'         => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password'     => Illuminate\Support\Facades\Password::class,
        'Queue'        => Illuminate\Support\Facades\Queue::class,
        'Redirect'     => Illuminate\Support\Facades\Redirect::class,
        'Redis'        => Illuminate\Support\Facades\Redis::class,
        'Request'      => Illuminate\Support\Facades\Request::class,
        'Response'     => Illuminate\Support\Facades\Response::class,
        'Route'        => Illuminate\Support\Facades\Route::class,
        'Schema'       => Illuminate\Support\Facades\Schema::class,
        'Session'      => Illuminate\Support\Facades\Session::class,
        'Storage'      => Illuminate\Support\Facades\Storage::class,
        'URL'          => Illuminate\Support\Facades\URL::class,
        'Validator'    => Illuminate\Support\Facades\Validator::class,
        'View'         => Illuminate\Support\Facades\View::class,

        // Custom facades.
        'Adldap'         => Adldap\Laravel\Facades\Adldap::class,
        'Nestable'       => Nestable\Facades\NestableService::class,
        'Process'        => App\Support\Facades\ProcessManager::class,
        'ProcessFactory' => App\Support\Facades\ProcessFactory::class,
    ],

    // Used to resolve model class from a string, usually stored into the database or as a route parameter.
    // @note: Instead of maintaining this list manually, we could possibly read from a seperate cached file, which could be generated from an artisan command (i.e. app:generate-entities-map).
    'entity_types' => [
        'architecture-plan'            => App\Models\Project\ArchitecturePlan\ArchitecturePlan::class,
        'architecture-plan-assessment' => App\Models\Project\ArchitecturePlan\ArchitecturePlanAssessment::class,
        'business-case'                => App\Models\Project\BusinessCase\BusinessCase::class,
        'business-case-assessment'     => App\Models\Project\BusinessCase\BusinessCaseAssessment::class,
        'community'                    => App\Models\Community::class,
        'course'                       => App\Models\LearningProduct\Course::class,
        'departmental-benefit-type'    => App\Models\Project\BusinessCase\DepartmentalBenefitType::class,
        'government-priority'          => App\Models\Project\BusinessCase\GovernmentPriority::class,
        'internal-resource'            => App\Models\Project\BusinessCase\InternalResource::class,
        'learners-benefit-type'        => App\Models\Project\BusinessCase\LearnersBenefitType::class,
        'learning-product'             => App\Models\LearningProduct\LearningProduct::class,
        'learning-product-type'        => App\Models\LearningProduct\LearningProductType::class,
        'maintenance-fund'             => App\Models\Project\BusinessCase\MaintenanceFund::class,
        'organizational-unit'          => App\Models\OrganizationalUnit::class,
        'planned-product'              => App\Models\Project\ArchitecturePlan\ArchitecturePlan::class,
        'potential-solution-type'      => App\Models\Project\BusinessCase\PotentialSolutionType::class,
        'process-form-decision'        => App\Models\Process\ProcessFormDecision::class,
        'process-instance'             => App\Models\Process\ProcessInstance::class,
        'process-instance-form'        => App\Models\Process\ProcessInstanceForm::class,
        'project'                      => App\Models\Project\Project::class,
        'request-source'               => App\Models\Project\BusinessCase\RequestSource::class,
        'risk-type'                    => App\Models\Project\BusinessCase\RiskType::class,
        'risk-impact-level'            => App\Models\Project\BusinessCase\RiskImpactLevel::class,
        'risk-probability-level'       => App\Models\Project\BusinessCase\RiskProbabilityLevel::class,
        'role'                         => App\Models\User\Role::class,
        'salary-fund'                  => App\Models\Project\BusinessCase\SalaryFund::class,
        'state'                        => App\Models\State::class,
        'timeframe'                    => App\Models\Project\BusinessCase\Timeframe::class,
    ],
];
