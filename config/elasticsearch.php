<?php

return [
    'hosts' => [
        env('ELASTICSEARCH_HOST', 'http://elasticsearch:9200'),
    ],
    'user' => env('ELASTICSEARCH_USERNAME'),
    'password' => env('ELASTICSEARCH_PASSWORD'),
    'ssl_verification' => env('ELASTICSEARCH_SSL_VERIFY', false),
    'cloud_id' => env('ELASTICSEARCH_CLOUD_ID'),
    'api_key' => env('ELASTICSEARCH_API_KEY'),
];

