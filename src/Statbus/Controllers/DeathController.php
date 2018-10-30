<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Models\Death as Death;
use Statbus\Controllers\Controller as Controller;

class DeathController Extends Controller{

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);

    $this->router = $this->container->get('router');
    $this->deathModel = new Death($this->container->get('settings')['statbus']);

    $this->pages = ceil($this->DB->cell("SELECT count(tbl_death.id) FROM tbl_death") / $this->per_page);

    $this->breadcrumbs['Deaths'] = $this->router->pathFor('death.index');

    $this->url = $this->router->pathFor('death.index');
  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $deaths = $this->DB->run("SELECT 
        tbl_death.id,
        tbl_death.pod,
        tbl_death.x_coord AS x,
        tbl_death.y_coord AS y,
        tbl_death.z_coord AS z,
        tbl_death.server_port AS port,
        tbl_death.round_id AS round,
        tbl_death.mapname,
        tbl_death.tod,
        tbl_death.job,
        tbl_death.special,
        tbl_death.name,
        tbl_death.byondkey,
        tbl_death.laname,
        tbl_death.lakey,
        tbl_death.bruteloss AS brute,
        tbl_death.brainloss AS brain,
        tbl_death.fireloss AS fire,
        tbl_death.oxyloss AS oxy,
        tbl_death.toxloss AS tox,
        tbl_death.cloneloss AS clone,
        tbl_death.staminaloss AS stamina,
        tbl_death.last_words,
        tbl_death.suicide
        FROM tbl_death
        LEFT JOIN tbl_round ON tbl_round.id = tbl_death.round_id
        WHERE tbl_round.end_datetime IS NOT NULL
        ORDER BY tbl_death.tod DESC
        LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($deaths as &$death){
      $death = $this->deathModel->parseDeath($death);
    }
    return $this->view->render($response, 'death/listing.tpl',[
      'deaths'      => $deaths,
      'death'       => $this,
      'wide'        => true,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function DeathsForRound($request, $response, $args) {
    if(isset($args['round'])) {
      $round = filter_var($args['round'], FILTER_VALIDATE_INT);
    }
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_death.id) FROM tbl_death WHERE tbl_death.round_id = ?", $round) / $this->per_page);
    $deaths = $this->DB->run("SELECT 
        tbl_death.id,
        tbl_death.pod,
        tbl_death.x_coord AS x,
        tbl_death.y_coord AS y,
        tbl_death.z_coord AS z,
        tbl_death.server_port AS port,
        tbl_death.round_id AS round,
        tbl_death.mapname,
        tbl_death.tod,
        tbl_death.job,
        tbl_death.special,
        tbl_death.name,
        tbl_death.byondkey,
        tbl_death.laname,
        tbl_death.lakey,
        tbl_death.bruteloss AS brute,
        tbl_death.brainloss AS brain,
        tbl_death.fireloss AS fire,
        tbl_death.oxyloss AS oxy,
        tbl_death.toxloss AS tox,
        tbl_death.cloneloss AS clone,
        tbl_death.staminaloss AS stamina,
        tbl_death.last_words,
        tbl_death.suicide
        FROM tbl_death
        LEFT JOIN tbl_round ON tbl_round.id = tbl_death.round_id
        WHERE tbl_round.end_datetime IS NOT NULL
        AND tbl_death.round_id = ?
        ORDER BY tbl_death.tod DESC
        LIMIT ?,?", 
          $round,
          ($this->page * $this->per_page) - $this->per_page,
          $this->per_page
        );
    foreach ($deaths as &$death){
      $death = $this->deathModel->parseDeath($death);
    }

    $this->breadcrumbs['Round '.$args['round']] = $this->router->pathFor('round.single',['id'=>$args['round']]);

    $this->url = $this->router->pathFor('death.round',['round'=>$args['round']]);

    return $this->view->render($response, 'death/listing.tpl',[
      'deaths'      => $deaths,
      'death'       => $this,
      'wide'        => true,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function single($request, $response, $args) {
    $death = $this->DB->row("SELECT 
        tbl_death.id,
        tbl_death.pod,
        tbl_death.x_coord AS x,
        tbl_death.y_coord AS y,
        tbl_death.z_coord AS z,
        tbl_death.server_port AS port,
        tbl_death.round_id AS round,
        tbl_death.mapname,
        tbl_death.tod,
        tbl_death.job,
        tbl_death.special,
        tbl_death.name,
        tbl_death.byondkey,
        tbl_death.laname,
        tbl_death.lakey,
        tbl_death.bruteloss AS brute,
        tbl_death.brainloss AS brain,
        tbl_death.fireloss AS fire,
        tbl_death.oxyloss AS oxy,
        tbl_death.toxloss AS tox,
        tbl_death.cloneloss AS clone,
        tbl_death.staminaloss AS stamina,
        tbl_death.last_words,
        tbl_death.suicide
        FROM tbl_death
        LEFT JOIN tbl_round ON tbl_round.id = tbl_death.round_id
        WHERE tbl_round.end_datetime IS NOT NULL
        AND tbl_death.id = ?", $args['id']);
      $death = $this->deathModel->parseDeath($death);
    $this->breadcrumbs[$death->id] = $this->router->pathFor('death.single',['id'=>$death->id]);
    return $this->view->render($response, 'death/death.tpl',[
      'death'       => $death,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function lastWords($request, $response, $args) {
    $deaths = $this->DB->run("SELECT 
        tbl_death.id,
        tbl_death.last_words
        FROM tbl_death
        LEFT JOIN tbl_round ON tbl_round.id = tbl_death.round_id
        WHERE tbl_death.last_words IS NOT NULL
        AND tbl_round.end_datetime IS NOT NULL
        GROUP BY tbl_death.last_words
        ORDER BY RAND()
        LIMIT 0, 1000");
    $this->breadcrumbs['Last Words'] = $this->router->pathFor('death.lastwords');
    return $this->view->render($response, 'death/lastwords.tpl',[
      'deaths'       => $deaths,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function deathMap($round){
    $deaths = $this->DB->run("SELECT 
        tbl_death.id,
        tbl_death.pod,
        tbl_death.x_coord AS x,
        tbl_death.y_coord AS y,
        tbl_death.tod,
        tbl_death.job,
        tbl_death.special,
        tbl_death.name,
        tbl_death.byondkey,
        tbl_death.laname,
        tbl_death.lakey,
        tbl_death.suicide
        FROM tbl_death
        LEFT JOIN tbl_round ON tbl_round.id = tbl_death.round_id
        WHERE tbl_round.end_datetime IS NOT NULL
        AND tbl_death.z_coord = 2
        AND tbl_death.round_id = ?
        ORDER BY tbl_death.tod DESC", $round);
    return json_encode($deaths);
  }
}