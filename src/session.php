<?php 
ini_set('session.gc_maxlifetime', 432000);
ini_set('session.cookie_lifetime', 432000);

$secure = true;
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
  $secure = false;
}
session_set_cookie_params(432000,'/',parse_url($_SERVER['REQUEST_URI'],PHP_URL_HOST),$secure, TRUE);

if(php_sapi_name() != 'cli'){
  if(!getenv('DEBUG')){
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => $secure
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
