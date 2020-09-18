<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();
$app->add($container->get('csrf'));

//Index URL
$app->get('/', \Statbus\Controllers\StatbusController::class . ':index')->setName('statbus');

$app->get('/election', \Statbus\Controllers\StatbusController::class . ':electionManager')->setName('election');

$app->post('/election', \Statbus\Controllers\StatbusController::class . ':electionManager')->setName('election');

//Name vote
$app->get('/names', \Statbus\Controllers\NameVoteController::class . ':index')->setName('nameVoter');
$app->post('/names', \Statbus\Controllers\NameVoteController::class . ':cast')->setName('nameVoter.cast');

$app->get('/names/rank/{rank}', \Statbus\Controllers\NameVoteController::class . ':rankings')->setName('nameVoter.results');

$app->get('/ticket/{identifier}', \Statbus\Controllers\TicketController::class . ':publicTicket')->setName('publicTicket');

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

  //My notes/messages
  $this->get('/me/messages[/page/{page}]', \Statbus\Controllers\PlayerController::class . ':getMyMessages')->setName('me.messages');

  //My rounds
  $this->get('/me/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':getMyRounds')->setName('me.rounds');

  $this->get('/me/tickets[/page/{page}]', \Statbus\Controllers\TicketController::class . ':myTickets')->setName('me.tickets');

  $this->get('/me/tickets/{round}/{ticket}', \Statbus\Controllers\TicketController::class . ':myTicket')->setName('me.tickets.single');

  $this->post('/me/tickets/{round}/{ticket}', \Statbus\Controllers\TicketController::class . ':myTicket')->setName('me.tickets.public');

})->add(new \Statbus\Middleware\UserGuard($container, 1));

//Public player pages
$app->group('', function () {
  //CHEEVOS
  $this->get('/player/{ckey:[a-z0-9]+}', \Statbus\Controllers\PlayerController::class . ':getPlayerPublic')->setName('player.public');
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
  $this->get('/rounds/{id:[0-9]+}/logs/{file:[a-zA-Z.]+}[/{format}]', \Statbus\Controllers\RoundController::class . ':getLogFile')->setName('round.log');

  //Single - Also handles single stat views!
  $this->get('/rounds/{id:[0-9]+}[/{stat}]', \Statbus\Controllers\RoundController::class . ':single')->setName('round.single');
});

//Stat Pages
$app->group('', function () {

  //List Stats
  $this->get('/stats', \Statbus\Controllers\StatController::class . ':list')->setName('stat.list');

  //Collated data
  $this->get('/stats/{stat}[/{version}]', \Statbus\Controllers\StatController::class . ':collate')->setName('stat.collate');

  //Collated data charted
  $this->get('/stats/{stat}/chart', \Statbus\Controllers\StatController::class . ':chart')->setName('stat.chart');

  //Round Listing
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
  $this->get('/info/admins', \Statbus\Controllers\StatbusController::class . ':DoAdminsPlay')->setName('admin_connections');

  //Admin Activity
  $this->get('/info/adminlogs[/page/{page}]', \Statbus\Controllers\StatbusController::class . ':adminLogs')->setName('admin_logs');

  //Population Data
  $this->get('/info/population', \Statbus\Controllers\StatbusController::class . ':popGraph')->setName('population');

  //Playtime graphs
  $this->get('/info/playtime', \Statbus\Controllers\StatbusController::class . ':last30Days')->setName('playtime');


  //Win-Loss Ratios
  $this->get('/info/winloss', \Statbus\Controllers\RoundController::class . ':winLoss')->setName('winloss');

  //Win-Loss Ratios
  $this->get('/info/mapularity', \Statbus\Controllers\StatbusController::class . ':mapularity')->setName('mapularity');


});

//Library & Art Gallery
$app->group('', function () {

  //Index
  $this->get('/library[/page/{page}]', \Statbus\Controllers\LibraryController::class . ':index')->setName('library.index');

  //Single Book
  $this->get('/library/{id:[0-9]+}', \Statbus\Controllers\LibraryController::class . ':single')->setName('library.single');

  //Delete Book (admin only)
  $this->post('/library/{id:[0-9]+}/delete', \Statbus\Controllers\LibraryController::class . ':deleteBook')->setName('library.delete');

  //Gallery Index
  $this->map(['GET','POST'], '/library/gallery[/{server}]', \Statbus\Controllers\LibraryController::class . ':artGallery')->setName('gallery.index');

})->add(new \Statbus\Middleware\UserGuard($container, 0));

//Polls
$app->group('', function () {

  //Index
  $this->get('/polls[/page/{page}]', \Statbus\Controllers\PollController::class . ':index')->setName('poll.index');

  //Single Poll
  $this->get('/polls/{id:[0-9]+}', \Statbus\Controllers\PollController::class . ':single')->setName('poll.single');

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

  //Player rounds
  $this->get('/tgdb/player/{ckey:[a-z0-9]+}/rounds[/page/{page}]', \Statbus\Controllers\RoundController::class . ':getPlayerRounds')->setName('player.rounds');

  //Player messages
  $this->get('/tgdb/player/{ckey:[a-z0-9]+}/messages[/page/{page}]', \Statbus\Controllers\PlayerController::class . ':getPlayerMessages')->setName('player.messages');

  //Typeahead
  $this->get('/tgdb/suggest', \Statbus\Controllers\PlayerController::class . ':findCkeys')->setName('player.suggest');

  //Admin Activity
  $this->get('/tgdb/admin/{ckey:[a-z0-9]+}', \Statbus\Controllers\PlayerController::class . ':getAdmin')->setName('admin.single');

  //Feedback link
  $this->get('/tgdb/feedback', \Statbus\Controllers\UserController::class . ':addFeedback')->setName('admin.feedback');

  $this->post('/tgdb/feedback', \Statbus\Controllers\UserController::class . ':addFeedback')->setName('admin.feedback');

  //Tickets!
  $this->get('/tgdb/tickets[/page/{page}]', \Statbus\Controllers\TicketController::class . ':index')->setName('ticket.index');

  $this->get('/tgdb/tickets/{round}', \Statbus\Controllers\TicketController::class . ':roundTickets')->setName('ticket.round');

  $this->get('/tgdb/tickets/{round}/{ticket}', \Statbus\Controllers\TicketController::class . ':single')->setName('ticket.single');

  //Character Name Search
  $this->get('/tgdb/name2ckey', \Statbus\Controllers\PlayerController::class . ':name2ckey')->setName('name2ckey');

  $this->post('/tgdb/name2ckey', \Statbus\Controllers\PlayerController::class . ':name2ckey')->setName('name2ckey');

})->add(new \Statbus\Middleware\UserGuard($container, 2));

};