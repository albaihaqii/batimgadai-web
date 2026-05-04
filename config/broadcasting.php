<?php

return [
    'default' => env('BROADCAST_DRIVER', 'log'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => filter_var(env('PUSHER_APP_USE_TLS', true), FILTER_VALIDATE_BOOLEAN),
                'host' => env('PUSHER_APP_HOST', '127.0.0.1'),
                'port' => env('PUSHER_APP_PORT', 6001),
                'scheme' => env('PUSHER_APP_SCHEME', 'http'),
                'encrypted' => filter_var(env('PUSHER_APP_USE_TLS', true), FILTER_VALIDATE_BOOLEAN),
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],
    ],
];
