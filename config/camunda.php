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
    'groups'      => [
        'admin' => env('CAMUNDA_ADMIN_GROUP', ''),
        'user'  => env('CAMUNDA_USER_GROUP')
    ],
    'storage' => env('CAMUNDA_STORAGE', ''),
];
