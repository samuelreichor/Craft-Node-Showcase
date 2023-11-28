<?php
return [
    'auto' => [
        'transforms' => [
            ['width' => 200],
            ['width' => 400],
            ['width' => 800],
            ['width' => 1600],
        ],
    ],
    'square' => [
        'transforms' => [
            ['width' => 200],
            ['width' => 400],
            ['width' => 800],
            ['width' => 1600],
        ],
        'defaults' => [
            'ratio' => 1,
        ],
    ],
    'landscape' => [
        'transforms' => [
            ['width' => 200],
            ['width' => 400],
            ['width' => 800],
            ['width' => 1600],
        ],
        'defaults' => [
            'ratio' => 3/2,
        ],
    ],
    'portrait' => [
        'transforms' => [
            ['width' => 200],
            ['width' => 400],
            ['width' => 800],
            ['width' => 1600],
        ],
        'defaults' => [
            'ratio' => 2/3,
        ],
    ],
    'dominantColor' => [
        'transforms' => [
            ['width' => 50],
        ],
    ],
];
