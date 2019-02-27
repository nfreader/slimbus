<?php

use Slim\Http\Request;
use Slim\Http\Response;

// $app->add(new \Statbus\Middleware\OpenGraph($container));
$container = $app->getContainer();
$app->add($container->get('csrf'));

//Index URL
$app->get('/', \Statbus\Controllers\StatbusController::class . ':index')->setName('statbus');

$app->get('/election', \Statbus\Controllers\StatbusController::class . ':electionManager')->setName('election');

//Name vote
$app->get('/names', \Statbus\Controllers\NameVoteController::class . ':index')->setName('nameVoter');
$app->post('/names', \Statbus\Controllers\NameVoteController::class . ':cast')->setName('nameVoter.cast');

$app->get('/names/rank/{rank}', \Statbus\Controllers\NameVoteController::class . ':rankings')->setName('nameVoter.results');

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

  //My role time
  $this->get('/me/roles', \Statbus\Controllers\PlayerController::class . ':getPlayerRoleTime')->setName('me.roles');
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

  //Game logs
  $this->get('/rounds/{id:[0-9]+}/logs/game[/page/{page}]', \Statbus\Controllers\RoundController::class . ':getGameLogs')->setName('round.gamelogs');

  //Single log file
  $this->get('/rounds/{id:[0-9]+}/logs/{file:[a-zA-Z.]+}[/{raw}]', \Statbus\Controllers\RoundController::class . ':getLogFile')->setName('round.log');

  //Single - Also handles single stat views!
  $this->get('/rounds/{id:[0-9]+}[/{stat}]', \Statbus\Controllers\RoundController::class . ':single')->setName('round.single');
});

//Stat Pages
$app->group('', function () {
  $this->get('/stats/{stat}/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':getRoundsWithStat')->setName('stat.rounds');
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
  $this->get('/info/admins[/wiki]', \Statbus\Controllers\StatbusController::class . ':DoAdminsPlay')->setName('admin_connections');

  //Admin Activity
  $this->get('/info/adminlogs[/page/{page}]', \Statbus\Controllers\StatbusController::class . ':adminLogs')->setName('admin_logs');

  //Population Data
  $this->get('/info/population', \Statbus\Controllers\StatbusController::class . ':popGraph')->setName('population');


});

//Library
$app->group('', function () {

  //Index
  $this->get('/library[/page/{page}]', \Statbus\Controllers\LibraryController::class . ':index')->setName('library.index');

  //Single Book
  $this->get('/library/{id:[0-9]+}', \Statbus\Controllers\LibraryController::class . ':single')->setName('library.single');

  //Delete Book (admin only)
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

  //Single Player Role Time View
  $this->get('/tgdb/player/{ckey:[a-z0-9]+}/roles', \Statbus\Controllers\PlayerController::class . ':getPlayerRoleTime')->setName('player.roletime');

  //Typeahead
  $this->get('/tgdb/suggest', \Statbus\Controllers\PlayerController::class . ':findCkeys')->setName('player.suggest');

  //Admin Activity
  $this->get('/tgdb/admin/{ckey:[a-z0-9]+}', \Statbus\Controllers\PlayerController::class . ':getAdmin')->setName('admin.single');

  //Feedback link
  $this->get('/tgdb/feedback', \Statbus\Controllers\UserController::class . ':addFeedback')->setName('admin.feedback');

  $this->post('/tgdb/feedback', \Statbus\Controllers\UserController::class . ':addFeedback')->setName('admin.feedback');

})->add(new \Statbus\Middleware\UserGuard($container));