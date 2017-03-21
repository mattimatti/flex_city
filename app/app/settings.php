<?php
return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => false,
                'debug' => true,
                'auto_reload' => true
            ]
        ],
        
        'database' => [
                'host' => 'localhost',
                'user' => 'root',
                'password' => '',
                'dbname' => 'symfony'
        ],
/*
        // View settings
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true,
            ],
        ],
*/
        'doctrine' => [
            'entities_path' => __DIR__ . '/src/Dao'
        ],
        
        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__ . '/../log/app.log'
        ]
    ]
];
