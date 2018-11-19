<?php
return [
    'settings' => [
        'refresh_key'            => (bool) getenv('REFRESH_KEY') ?: FALSE,
        'displayErrorDetails'    => (bool) getenv('DISPLAY_ERRORS') ?: FALSE, 
        'addContentLengthHeader' => false,
        'debug'                  => (bool) getenv('DEBUG') ?: FALSE,
        'twig' => [
            'template_path'  => __DIR__ . '/../templates/',
            'template_cache' => __DIR__ . '/../tmp/twig/',
            'twig_debug'     => (bool) getenv('DEBUG') ?: FALSE,
            'date_format'    => 'Y-m-d H:i:s',
            'auto_reload'    => FALSE
        ],
        'database' => [
            'primary' => [
                'host'      => getenv('DB_HOST')     ?: '127.0.0.1',
                'port'      => getenv('DB_PORT')     ?: 3306,
                'database'  => getenv('DB_DATABASE') ?: 'tgdb',
                'username'  => getenv('DB_USERNAME') ?: 'root',
                'password'  => getenv('DB_PASSWORD') ?: '123',
                'prefix'    => getenv('DB_PREFIX')   ?: 'ss13',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'strict'    => false,
                'engine'    => null,
                'canFail'   => FALSE
            ],
            'alt' => [
                'host'      => getenv('ALT_DB_HOST')     ?: '127.0.0.1',
                'port'      => getenv('ALT_DB_PORT')     ?: 3306,
                'database'  => getenv('ALT_DB_DATABASE') ?: 'statbus',
                'username'  => getenv('ALT_DB_USERNAME') ?: 'root',
                'password'  => getenv('ALT_DB_PASSWORD') ?: '123',
                'prefix'    => getenv('ALT_DB_PREFIX')   ?: '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'strict'    => false,
                'engine'    => null,
                'canFail'   => TRUE
            ]
        ],
    ],
];
