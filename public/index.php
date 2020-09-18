<?php
if (PHP_SAPI == 'cli-server') {
  if(strpos($_SERVER['SCRIPT_NAME'], '/tmp') !== FALSE){
    return false;
  }
  // $_SERVER['SCRIPT_NAME'] = '/index.php';
}

//Configure session settings
require __DIR__ . '/../src/session.php';

//Load up on libs
require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();

if(getenv('DEBUG')){
  ini_set('xdebug.var_display_max_depth',-1);
  ini_set('xdebug.var_display_max_data',-1);
  ini_set('xdebug.var_display_max_children',-1);
}

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$settings['settings']['statbus'] = require __DIR__ . '/../src/conf/Statbus.php';

if(file_exists(__DIR__ . '/../src/conf/servers.json')){
  $settings['settings']['statbus']['servers'] = json_decode(file_get_contents(__DIR__ . '/../src/conf/servers.json'), true);
}

if(file_exists(__DIR__ . '/../src/conf/ranks.json')){
  $settings['settings']['statbus']['ranks'] = json_decode(file_get_contents(__DIR__ . '/../src/conf/ranks.json'), true);
}

if ($refresh = filter_input(INPUT_GET,'refresh', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
  if(password_verify($refresh, password_hash($settings['settings']['refresh_key'], PASSWORD_DEFAULT))){
    $settings['settings']['twig']['auto_reload'] = TRUE;
  }
}

// Instantiate the app
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require __DIR__ . '/../src/dependencies.php';
$dependencies($app);

// Remove trailing slashes
require __DIR__ . '/../src/trailingSlash.php';

$routes = require __DIR__ . '/../src/routes.php';
$routes($app);

// Run app
$app->run();
