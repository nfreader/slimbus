<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Round as Round;
use Statbus\Controllers\StatController as StatController;
use Statbus\Controllers\DeathController as DeathContorller;
use Statbus\Controllers\LogsController as LogsController;

class RoundController Extends Controller {

  private $columns = "tbl_round.id,
      tbl_round.initialize_datetime,
      tbl_round.start_datetime,
      tbl_round.shutdown_datetime,
      tbl_round.end_datetime,
      tbl_round.server_port AS port,
      tbl_round.commit_hash,
      tbl_round.game_mode AS mode,
      tbl_round.game_mode_result AS result,
      tbl_round.end_state,
      tbl_round.shuttle_name AS shuttle,
      tbl_round.map_name AS map,
      tbl_round.station_name,
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.initialize_datetime, tbl_round.shutdown_datetime)) AS duration,
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.start_datetime, tbl_round.end_datetime)) AS round_duration,
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.initialize_datetime, tbl_round.start_datetime)) AS init_time,
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.end_datetime, tbl_round.shutdown_datetime)) AS shutdown_time";
  
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_round.id) FROM tbl_round") / $this->per_page);

    $this->roundModel = new Round($this->container->get('settings')['statbus']);

    $this->breadcrumbs['Rounds'] = $this->router->pathFor('round.index');
  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $rounds = $this->DB->run("SELECT $this->columns
      FROM tbl_round
      WHERE tbl_round.shutdown_datetime IS NOT NULL
      ORDER BY tbl_round.shutdown_datetime DESC
      LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);

    foreach ($rounds as &$round){
      $round = $this->roundModel->parseRound($round);
    }
    if($rounds){
      $this->firstListing = $rounds[0]->end_datetime;
      $this->lastListing = end($rounds)->start_datetime;
    } else {
      $this->firstListing = null;
      $this->lastListing = null;
    }
    return $this->view->render($response, 'rounds/listing.tpl',[
      'rounds'      => $rounds,
      'round'       => $this,
      'wide'        => true,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function single($request, $response, $args) {
    $round = $this->getRound($args['id']);
    if(!$round->id) {
      return $this->view->render($response, 'base/error.tpl',[
        'code'    => 404,
        'message' => 'Round not found, or is ongoing',
        'link'    => $this->router->pathFor('round.index'),
        'linkText'=> 'Round Listing'
      ]);
    }
    if(isset($args['stat'])){
      return $this->stat($round, $args['stat'], $response);
    }
    $round->stats = (new StatController($this->DB))->getStatsForRound($round->id);
    $round->data = (new StatController($this->DB))->getStatsForRound($round->id,[
      'nuclear_challenge_mode',
      'testmerged_prs',
      'newscaster_stories'
    ]);
    return $this->view->render($response, 'rounds/round.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }

  public function stat(object $round, string $stat, $response){
    $stat = filter_var($stat, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $round->stat = (new StatController($this->DB))->getRoundStat($round->id, $stat);
    $url = parent::getFullURL($this->router->pathFor('round.single',[
      'id'   =>$round->id,
      'stat' =>$stat
    ]));
    $this->breadcrumbs[$stat] = $url;
    $this->ogdata['url'] = $url;
    $this->ogdata['description'] = "Stats for $stat from round $round->id on $round->server";
    return $this->view->render($response, 'stats/single.tpl',[
      'round'       => $round,
      'stat'        => $round->stat,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }

  public function stationNames($request, $response, $args){
    $names = $this->DB->run("SELECT station_name, id FROM tbl_round WHERE station_name IS NOT NULL ORDER BY RAND() DESC LIMIT 0, 1000;");
    return $this->view->render($response, 'rounds/stationnames.tpl',[
      'names'       => $names,
    ]);
  }

  public function mapView($request, $response, $args){
    $round = $this->getRound($args['id']);
    $deaths = (new DeathController($this->container))->deathMap($round->id);
    $explosions = (new StatController($this->DB))->getRoundStat($round->id, 'explosion', TRUE);
    $this->breadcrumbs['Map'] = $this->router->pathFor('round.map',[
      'id'   =>$round->id,
    ]);
    return $this->view->render($response, 'rounds/map.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs,
      'deaths'      => $deaths,
      'explosions'  => $explosions,
      'wide'        => true
    ]);
  }


  public function getRound(int $id){
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $round = $this->DB->row("SELECT $this->columns,
      MAX(next.id) AS next,
      MAX(prev.id) AS prev,
      COUNT(D.id) AS deaths
      FROM tbl_round
      LEFT JOIN tbl_round AS next ON next.id = tbl_round.id + 1
      LEFT JOIN tbl_round AS prev ON prev.id = tbl_round.id - 1 
      LEFT JOIN tbl_death AS D ON D.round_id = tbl_round.id
      WHERE tbl_round.id = ?
      AND tbl_round.shutdown_datetime IS NOT NULL", $id);
    $round = $this->roundModel->parseRound($round);
    $url = parent::getFullURL($this->router->pathFor('round.single',['id'=>$round->id]));
    $this->breadcrumbs[$round->id] = $url;
    $this->ogdata['url'] = $url;
    $this->ogdata['title'] = "Round #$round->id on $round->server";
    $this->ogdata['description'] = "A round of $round->mode on $round->map that lasted $round->duration and ended with $round->result and $round->deaths deaths.";
    return $round;
  }

  public function listLogs($request, $response, $args){
    $round = $this->getRound($args['id']);
    $logs = (new LogsController($this->container, $round))->listing();

    $url = parent::getFullURL($this->router->pathFor('round.logs',['id'=>$round->id]));

    $this->breadcrumbs['Logs'] = $url;

    $this->ogdata['url'] = $url;
    $this->ogdata['title'] = "Log listing for round #$round->id on $round->server";
    $this->ogdata['description'] = (($logs) ? count($logs) : 0)." log files available";

    return $this->view->render($response, 'rounds/logs.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata,
      'logs'        => $logs
    ]);
  }
  public function getLogFile($request, $response, $args){
    $round = $this->getRound($args['id']);
    $file = filter_var($args['file'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $raw = false;
    if(isset($args['raw'])) {
      $raw = filter_var($args['raw'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      if('raw' === $raw) {
        $raw = true;
      }
    }
    $logs = (new LogsController($this->container, $round))->getFile($file, $raw);

    $this->breadcrumbs['Logs'] = parent::getFullURL($this->router->pathFor('round.logs',[
      'id'   => $round->id,
    ]));

    $url = parent::getFullURL($this->router->pathFor('round.log',[
      'id'   => $round->id,
      'file' => $file
    ]));
    
    $this->breadcrumbs[$file] = $url;

    $this->ogdata['url'] = $url;
    $this->ogdata['title'] = "$file logfile for Round #$round->id on $round->server";
    $this->ogdata['description'] = (($logs) ? count($logs) : 0)." lines found in $file.";
    return $this->view->render($response, 'rounds/log.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata,
      'file'        => $logs,
      'filename'    => $file,
      'raw'         => $raw,
      'wide'        => true
    ]);
  }

  public function getGameLogs($request, $response, $args){
    $file = 'game.txt';
    $round = $this->getRound($args['id']);
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $round->page = $this->page;
    $this->pages = 1;
    $logs = (new LogsController($this->container, $round))->getGameLogs($this->page);
    $round->pages = (new LogsController($this->container, $round))->getPages();

    if(!$logs){
      return $this->view->render($response, 'base/error.tpl',[
        'message'  => "Alt DB not configured. No parsed logs available",
        'code'     => 500,
        'linkText' => "Back",
        'link'     => parent::getFullURL($this->router->pathFor('round.single',[
              'id'   =>$round->id,
            ]
          ))
      ]);
    }

    $url = parent::getFullURL($this->router->pathFor('round.log',[
      'id'   => $round->id,
      'file' => $file
    ]));

    $this->breadcrumbs['Logs'] = parent::getFullURL($this->router->pathFor('round.logs',['id'=>$round->id]));
    
    $this->breadcrumbs['Parsed Game Logs'] = $url;

    return $this->view->render($response, 'rounds/log.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata,
      'file'        => $logs,
      'filename'    => $file,
      'wide'        => true
    ]);
  }

  public function getRoundsWithStat($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    if(isset($args['stat'])) {
      $stat = filter_var($args['stat'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_feedback.id) FROM tbl_feedback WHERE key_name = ?", $stat) / $this->per_page);
    $rounds = $this->DB->run("SELECT $this->columns
      FROM tbl_feedback
      LEFT JOIN tbl_round ON tbl_feedback.round_id = tbl_round.id
      WHERE key_name = ?
      ORDER BY round_id DESC
      LIMIT ?,?", $stat, ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($rounds as &$round){
      $round = $this->roundModel->parseRound($round);
    }
    return $this->view->render($response, 'stats/rounds.tpl',[
      'rounds'      => $rounds,
      'round'       => $this,
      'wide'        => true,
      'stat'        => $stat,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function getRoundsForCkey($ckey){
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_round.id) FROM tbl_connection_log
        LEFT JOIN tbl_round ON tbl_connection_log.round_id = tbl_round.id
        WHERE tbl_connection_log.ckey = ?
        AND tbl_round.shutdown_datetime IS NOT NULL", $ckey) / $this->per_page);
    $rounds = $this->DB->run("SELECT $this->columns
      FROM tbl_connection_log
      LEFT JOIN tbl_round ON tbl_connection_log.round_id = tbl_round.id
      WHERE tbl_connection_log.ckey = ?
      AND tbl_round.shutdown_datetime IS NOT NULL
      ORDER BY tbl_connection_log.`datetime` DESC
      LIMIT ?,?", $ckey, ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($rounds as &$round){
      $round = $this->roundModel->parseRound($round);
    }
    return $rounds;
  }

  public function getMyRounds($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->user = $this->container->get('user');
    $rounds = $this->getRoundsForCkey($this->user->ckey);
    return $this->view->render($response, 'me/rounds.tpl',[
      'rounds'      => $rounds,
      'round'       => $this,
      'wide'        => true,
      'stat'        => $stat,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function getPlayerRounds($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    if(isset($args['ckey'])) {
      $ckey = filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    $rounds = $this->getRoundsForCkey($ckey);
    return $this->view->render($response, 'player/rounds.tpl',[
      'rounds'      => $rounds,
      'round'       => $this,
      'wide'        => true,
      'stat'        => $stat,
      'breadcrumbs' => $this->breadcrumbs,
      'ckey'        => $ckey
    ]);
  }
}