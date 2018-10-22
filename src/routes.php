<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

//Index URL
$app->get('/', \Statbus\Controllers\StatbusController::class . ':index')->setName('statbus');

//Rounds
$app->group('', function () {
  //Index
  $this->get('/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':index')->setName('round.index');
  
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