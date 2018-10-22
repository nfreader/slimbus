<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
// 

  $app->get('/', \Statbus\Controllers\RoundController::class . ':index')->setName('round.index');

$app->get('/home', \Statbus\HomeController::class . ':home');

//Rounds
$app->group('', function () {
  //Index
  $this->get('/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':index')->setName('round.index');
  
  //Single
  $this->get('/rounds/{id:[0-9]+}[/{stat}]', \Statbus\Controllers\RoundController::class . ':single')->setName('round.single');

  //Stat view
  // $this->get('/rounds/{id:[0-9]+}[/{stat}]', \Statbus\Controllers\StatController::class . ':roundStat')->setName('round.stat');
});

//Deaths
$app->group('', function () {
    $this->get('/deaths[/page/{page}]', \Statbus\Controllers\DeathController::class . ':index')->setName('death.index');

    $this->get('/deaths/lastwords', \Statbus\Controllers\DeathController::class . ':lastwords')->setName('death.lastwords');

    $this->get('/deaths/round/{round:[0-9]+}[/page/{page}]', \Statbus\Controllers\DeathController::class . ':DeathsForRound')->setName('death.round');
    
    $this->get('/deaths/{id:[0-9]+}', \Statbus\Controllers\DeathController::class . ':single')->setName('death.single');
});