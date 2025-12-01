<?php

return [
    'index' => env('SCOUT_PREFIX', 'commerce_') . env('APP_ENV', 'local'),

    'body' => [
        'settings' => [
            'max_result_window' => 20000,
        ],
    ],

    'hosts' => [
        env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200'),
    ],

    'basic_auth' => [
        'username' => env('ELASTICSEARCH_USERNAME'),
        'password' => env('ELASTICSEARCH_PASSWORD'),
    ],

    'soft_delete' => true,
];

