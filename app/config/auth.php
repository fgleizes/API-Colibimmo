<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'person',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'person',
        ],
    ],

    'providers' => [
        'person' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Person::class
        ]
    ]
];