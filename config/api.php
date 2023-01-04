<?php

return [
    'url' => [
        'prefix' => 'api'
    ],
    'http' => [
        'middleware' => [
            'default' => \Api\Http\Middleware\MiddlewareDefault::class,
            'middle1' => \Api\Http\Middleware\Middle1::class
        ]
    ]
];