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
        'user_model' => 'App\Models\User\User',
        'storage'    => 'camunda', //-> /storage/app/camunda
        'groups'     => [
            'admin' => 'camunda-admin',
            'user'  => 'lpa-user'
        ],
    ],

];
