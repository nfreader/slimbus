<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

session_start();

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

if(getenv('DEBUG')){
  ini_set('xdebug.var_display_max_depth',-1);
  ini_set('xdebug.var_display_max_data',-1);
  ini_set('xdebug.var_display_max_children',-1);
}

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$settings['statbus'] = require __DIR__ . '/src/conf/Statbus.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Register middleware
require __DIR__ . '/src/middleware.php';

// Remove trailing slashes
require __DIR__ . '/src/trailingSlash.php';

// Register routes
require __DIR__ . '/src/routes.php';

// Run app
$app->run();
