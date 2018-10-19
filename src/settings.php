<?php
return [
    'settings' => [
        'displayErrorDetails'    => getenv('DISPLAY_ERRORS') ?: TRUE, 
        'addContentLengthHeader' => false, // Allow the web server to send the 
        'twig' => [
            'template_path'  => __DIR__ . '/../templates/',
            'template_cache' => __DIR__ . '/../tmp/twig/',
            'twig_debug'     => getenv('DEBUG') ?: FALSE,
            'date_format'    => 'Y-m-d H:i:s'
        ],
        'database' => [
            'primary' => [
                'host'      => getenv('DB_HOST')    ?:     '127.0.0.1',
                'port'      => getenv('DB_PORT')    ?:     3306,
                'database'  => getenv('DB_DATABASE')?:     'tgdb',
                'username'  => getenv('DB_USERNAME')?:     'root',
                'password'  => getenv('DB_PASSWORD')?:     '123',
                'prefix'    => getenv('DB_PREFIX')  ?:     'ss13',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'strict' => false,
                'engine' => null,
            ]
        ],
    ],
];
