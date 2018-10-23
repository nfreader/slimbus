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


require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();

if(php_sapi_name() != 'cli'){
  if(!getenv('DEBUG')){
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => true
    ]);
  } else {
    session_start();
  }
  // Make sure we have a canary set
  if (!isset($_SESSION['canary'])) {
      session_regenerate_id(true);
      $_SESSION['canary'] = time();
  }
  // Regenerate session ID every five minutes:
  if ($_SESSION['canary'] < time() - 300) {
      session_regenerate_id(true);
      $_SESSION['canary'] = time();
  }
  
  //Set session expiry to five days
  $time = $_SERVER['REQUEST_TIME'];
  $timeout_duration = 432000;
  if (isset($_SESSION['LAST_ACTIVITY']) && 
     ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
      session_unset();
      session_destroy();
      session_start();
  }

  $_SESSION['LAST_ACTIVITY'] = $time;
}

if(getenv('DEBUG')){
  ini_set('xdebug.var_display_max_depth',-1);
  ini_set('xdebug.var_display_max_data',-1);
  ini_set('xdebug.var_display_max_children',-1);
}

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Remove trailing slashes
require __DIR__ . '/../src/trailingSlash.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
