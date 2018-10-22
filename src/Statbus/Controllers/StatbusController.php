<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Models\Death as Death;


class StatbusController {
  protected $container;
  protected $view;
  protected $DB;
  protected $router;

  public $page = 1;
  public $pages = 0;
  public $per_page = 60;

  public $breadcrumbs = [];

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->view = $this->container->get('view');
    $this->DB = $this->container->get('DB');
    $this->router = $this->container->get('router');
    $this->deathModel = new Death($this->container->get('settings')['statbus']);

    $this->pages = ceil($this->DB->cell("SELECT count(tbl_death.id) FROM tbl_death") / $this->per_page);

    $this->breadcrumbs['Deaths'] = $this->router->pathFor('death.index');

    $this->url = $this->router->pathFor('death.index');
  }

  public function index($request, $response, $args) {
    return $this->view->render($response, 'index.tpl',[
      'numbers' => $this->getBigNumbers()
    ]);
  }

  public function getBigNumbers(){
    $numbers = new \stdclass;

    $numbers->playtime = number_format($this->DB->row("SELECT sum(tbl_role_time.minutes) AS minutes FROM tbl_role_time WHERE tbl_role_time.job = 'Living';")->minutes);
    $numbers->deaths = number_format($this->DB->cell("SELECT count(id) as deaths FROM tbl_death;")+rand(-15,15));//fuzzed
    $numbers->rounds = number_format($this->DB->cell("SELECT count(id) as rounds FROM tbl_round;"));
    $numbers->books = number_format($this->DB->cell("SELECT count(id) as books FROM tbl_library;"));
    return $numbers;
  }
}