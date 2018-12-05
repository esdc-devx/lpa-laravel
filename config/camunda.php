<?php

return [
    // Connection configuration.
    'connection' => [
        'host' => env('CAMUNDA_HOST', ''),
        'port' => env('CAMUNDA_PORT', ''),
        'url'  => env('CAMUNDA_REST_URL', ''),
    ],

    // Authentication configurations.
    'authentication' => [
        'username' => env('CAMUNDA_USERNAME', ''),
        'password' => env('CAMUNDA_PASSWORD', ''),
        'salt'     => env('CAMUNDA_PASSWORD_SALT', ''),
    ],

    // Application configurations.
    'app' => [
        'url'        => env('CAMUNDA_APP_URL', 'http://localhost/process-engine'),
        'user_model' => 'App\Models\User\User',
        'storage'    => [
            'create' => [
                'camunda/mysql/create/mysql_engine_7.9.0.sql',
                'camunda/mysql/create/mysql_identity_7.9.0.sql',
                'camunda/mysql/create/mysql_init_7.9.0.sql',
            ],
            'revert'     => 'camunda/mysql/revert/camunda_revert.sql',
            'deployment' => 'camunda/deployments',
        ],
        'groups'     => [
            'admin' => 'camunda-admin',
            'user'  => 'lpa-user'
        ],
    ],

];
