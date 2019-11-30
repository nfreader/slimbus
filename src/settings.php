<?php
return [
    'settings' => [
        'refresh_key'            => getenv('REFRESH_KEY') ?: FALSE,
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
                'host'      => getenv('DB_HOST')     ?: '',
                'port'      => getenv('DB_PORT')     ?: '',
                'database'  => getenv('DB_DATABASE') ?: '',
                'username'  => getenv('DB_USERNAME') ?: '',
                'password'  => getenv('DB_PASSWORD') ?: '',
                'prefix'    => getenv('DB_PREFIX')   ?: '',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'strict'    => false,
                'engine'    => null,
                'canFail'   => FALSE
            ],
            'alt' => [
                'host'      => getenv('ALT_DB_HOST')     ?: '',
                'port'      => getenv('ALT_DB_PORT')     ?: '',
                'database'  => getenv('ALT_DB_DATABASE') ?: '',
                'username'  => getenv('ALT_DB_USERNAME') ?: '',
                'password'  => getenv('ALT_DB_PASSWORD') ?: '',
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
