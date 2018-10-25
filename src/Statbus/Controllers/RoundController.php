<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Round as Round;
use Statbus\Controllers\StatController as StatController;

class RoundController Extends Controller {
  
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
    $rounds = $this->DB->run("SELECT tbl_round.id,
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
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.end_datetime, tbl_round.shutdown_datetime)) AS shutdown_time
      FROM tbl_round

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
    $round = $this->DB->row("SELECT tbl_round.id,
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
      SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_round.end_datetime, tbl_round.shutdown_datetime)) AS shutdown_time,
      MAX(next.id) AS next,
      MAX(prev.id) AS prev,
      COUNT(D.id) AS deaths
      FROM tbl_round
      LEFT JOIN tbl_round AS next ON next.id = tbl_round.id + 1
      LEFT JOIN tbl_round AS prev ON prev.id = tbl_round.id - 1 
      LEFT JOIN tbl_death AS D ON D.round_id = tbl_round.id
      WHERE tbl_round.id = ?
      AND tbl_round.shutdown_datetime IS NOT NULL", $args['id']);
    if(!$round->id) {
      return $this->view->render($response, 'base/error.tpl',[
        'round' => $round,
      ]);
    }

    $round = $this->roundModel->parseRound($round);

    $this->breadcrumbs[$round->id] = $this->router->pathFor('round.single',['id'=>$round->id]);

    if(isset($args['stat'])){
      return $this->stat($round, $args['stat'], $response);
    }
    $round->stats = (new StatController($this->DB))->getStatsForRound($round->id);
    return $this->view->render($response, 'rounds/round.tpl',[
      'round'       => $round,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function stat(object $round, string $stat, $response){
    $round->stat = (new StatController($this->DB))->getRoundStat($round->id, $stat);
    $this->breadcrumbs[$stat] = $this->router->pathFor('round.single',[
      'id'   =>$round->id,
      'stat' =>$stat
    ]);
    return $this->view->render($response, 'stats/stat.tpl',[
      'round'       => $round,
      'stat'        => $round->stat,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function stationNames($request, $response, $args){
    $names = $this->DB->run("SELECT station_name, id FROM tbl_round WHERE station_name IS NOT NULL ORDER BY RAND() DESC LIMIT 0, 1000;");
    return $this->view->render($response, 'rounds/stationnames.tpl',[
      'names'       => $names,
    ]);
  }
}