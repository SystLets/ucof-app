<?php

return [
    'default' => env('DB_CONNECTION', 'mongodb'),
    'connections' => [
        'mongodb' => [
            'driver' => 'mongodb',
            'dsn' => env('MONGODB_URI', 'mongodb://mongodb:27017'),
            'database' => env('MONGODB_DATABASE', 'ucof'),
        ],
    ],
];
