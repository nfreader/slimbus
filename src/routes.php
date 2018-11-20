<?php

use Slim\Http\Request;
use Slim\Http\Response;

// $app->add(new \Statbus\Middleware\OpenGraph($container));
$container = $app->getContainer();
$app->add($container->get('csrf'));

//Index URL
$app->get('/', \Statbus\Controllers\StatbusController::class . ':index')->setName('statbus');

//Auth
$app->group('', function () {

  //Confirmation screen
  $this->get('/auth', \Statbus\Controllers\AuthController::class . ':auth')->setName('auth');

  //Redirect
  $this->get('/auth_redirect', \Statbus\Controllers\AuthController::class . ':auth_redirect')->setName('auth_redirect');

  //Return URL
  $this->get('/auth_return', \Statbus\Controllers\AuthController::class . ':auth_return')->setName('auth_return');

  //Return URL
  $this->get('/logout', \Statbus\Controllers\AuthController::class . ':logout')->setName('logout');
});

//Me
$app->group('', function () {

  //Index
  $this->get('/me', \Statbus\Controllers\UserController::class . ':me')->setName('me');
});

//Rounds
$app->group('', function () {

  //Index
  $this->get('/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':index')->setName('round.index');

  //Station Names
  $this->get('/rounds/stations', \Statbus\Controllers\RoundController::class . ':stationNames')->setName('round.stations');
  
  //Map view
  $this->get('/rounds/{id:[0-9]+}/map', \Statbus\Controllers\RoundController::class . ':mapView')->setName('round.map');

  //Logs
  $this->get('/rounds/{id:[0-9]+}/logs', \Statbus\Controllers\RoundController::class . ':listLogs')->setName('round.logs');

  //Single log file
  $this->get('/rounds/{id:[0-9]+}/logs/{file:[a-zA-Z.]+}[/{raw}]', \Statbus\Controllers\RoundController::class . ':getLogFile')->setName('round.log');

  //Single - Also handles single stat views!
  $this->get('/rounds/{id:[0-9]+}[/{stat}]', \Statbus\Controllers\RoundController::class . ':single')->setName('round.single');
});

//Deaths
$app->group('', function () {

  //Index
  $this->get('/deaths[/page/{page}]', \Statbus\Controllers\DeathController::class . ':index')->setName('death.index');

  //Last words listing
  $this->get('/deaths/lastwords', \Statbus\Controllers\DeathController::class . ':lastwords')->setName('death.lastwords');

  //Death listing for rounds
  $this->get('/deaths/round/{round:[0-9]+}[/page/{page}]', \Statbus\Controllers\DeathController::class . ':DeathsForRound')->setName('death.round');

  //Single death view
  $this->get('/deaths/{id:[0-9]+}', \Statbus\Controllers\DeathController::class . ':single')->setName('death.single');
});

//Info pages
$app->group('', function () {

  //Admin Activity
  $this->get('/info/admins', \Statbus\Controllers\StatbusController::class . ':DoAdminsPlay')->setName('admin_connections');

  $this->get('/info/population', \Statbus\Controllers\StatbusController::class . ':popGraph')->setName('population');

  //Admin Activity
  $this->get('/info/adminlogs[/page/{page}]', \Statbus\Controllers\StatbusController::class . ':adminLogs')->setName('admin_logs');


});

//Library
$app->group('', function () {

  //Index
  $this->get('/library[/page/{page}]', \Statbus\Controllers\LibraryController::class . ':index')->setName('library.index');

  //Single Book
  $this->get('/library/{id:[0-9]+}', \Statbus\Controllers\LibraryController::class . ':single')->setName('library.single');

  $this->post('/library/{id:[0-9]+}/delete', \Statbus\Controllers\LibraryController::class . ':deleteBook')->setName('library.delete');
});

//TGDB
$app->group('', function () {

  //Index
  $this->get('/tgdb', \Statbus\Controllers\StatbusController::class . ':tgdbIndex')->setName('tgdb');

  //Message Index
  $this->get('/tgdb/messages[/page/{page}]', \Statbus\Controllers\MessageController::class . ':listing')->setName('message.index');

  //Single Message View
  $this->get('/tgdb/messages/{id:[0-9]+}', \Statbus\Controllers\MessageController::class . ':single')->setName('message.single');

  //Single Player View
  $this->get('/tgdb/player/{ckey:[a-z0-9]+}', \Statbus\Controllers\PlayerController::class . ':getPlayer')->setName('player.single');

})->add(new \Statbus\Middleware\UserGuard($container));