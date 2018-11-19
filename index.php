<?php
if (PHP_SAPI == 'cli-server') {
  // To help the built-in PHP dev server, check if the request was actually for
  // something which should probably be served as a static file
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if(strpos($file, "/logs/") !== FALSE){
    $_SERVER['SCRIPT_NAME'] = 'index.php';
  }
}

//Configure session settings 
require __DIR__ . '/src/session.php';

//Load up on libs
require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

if(getenv('DEBUG')){
  ini_set('xdebug.var_display_max_depth',-1);
  ini_set('xdebug.var_display_max_data',-1);
  ini_set('xdebug.var_display_max_children',-1);
}

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$settings['settings']['statbus'] = require __DIR__ . '/src/conf/Statbus.php';
if(file_exists(__DIR__ . '/src/conf/servers.json')){
  $settings['settings']['statbus']['servers'] = json_decode(file_get_contents(__DIR__ . '/src/conf/servers.json'), true);
}

if(file_exists(__DIR__ . '/src/conf/ranks.json')){
  $settings['settings']['statbus']['ranks'] = json_decode(file_get_contents(__DIR__ . '/src/conf/ranks.json'), true);
}
if ($settings['settings']['refresh_key'] == filter_input(INPUT_GET,'refresh', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)){
  $settings['settings']['twig']['auto_reload'] = TRUE;
  print("Twig cache has been cleared");
}
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Remove trailing slashes
require __DIR__ . '/src/trailingSlash.php';

// Register routes
require __DIR__ . '/src/routes.php';

// Run app
$app->run();

