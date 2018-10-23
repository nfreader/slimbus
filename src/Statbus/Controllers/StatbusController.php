<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Death as Death;
use Statbus\Models\Player as Player;

class StatbusController extends Controller {


  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
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

  public function doAdminsPlay($request, $response, $args){
    $args = $request->getQueryParams();
    if(isset($args['interval'])) {
      $options = array(
        'options'=>array(
        'default'=>20,
        'min_range'=>2,
        'max_range'=>30
      ));
      $interval = filter_var($args['interval'], FILTER_VALIDATE_INT, $options);
    } else {
      $interval = 20;
    }
    $admins = $this->DB->run("SELECT A.ckey, 
      A.rank,
      R.flags,
      R.exclude_flags,
      R.can_edit_flags,
      (SELECT count(C.id) FROM tbl_connection_log AS C
      WHERE A.ckey = C.ckey AND C.datetime BETWEEN CURDATE() - INTERVAL ? DAY AND CURDATE()) AS connections,
      (SELECT sum(G.delta) FROM tbl_role_time_log AS G
      WHERE A.ckey = G.ckey AND G.job = 'Ghost' AND G.datetime BETWEEN CURDATE() - INTERVAL ? DAY AND CURDATE()) AS ghost,
      (SELECT sum(L.delta) FROM tbl_role_time_log AS L
      WHERE A.ckey = L.ckey
      AND L.job = 'Living'
      AND L.datetime BETWEEN CURDATE() - INTERVAL ? DAY AND CURDATE()
      ) AS living
      FROM tbl_admin as A
      LEFT JOIN tbl_admin_ranks AS R ON A.rank = R.rank
      GROUP BY A.ckey;", $interval, $interval, $interval);
    $perms = $this->container->get('settings')['statbus']['perm_flags'];

    $pm = new Player($this->container->get('settings')['statbus']);
    foreach ($admins as &$a){
      foreach($perms as $p => $b){
        if ($a->flags & $b){
          $a->permissions[] = $p;
        }
      }
      $a->total = $a->ghost + $a->living;
      $a = $pm->parsePlayer($a);
    }
    return $this->view->render($response, 'info/admins.tpl',[
      'admins'   => $admins,
      'interval' => $interval,
      'perms'    => $perms,
      'wide'     => true
    ]);
  }

  public function adminLogs($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_admin_log.id) FROM tbl_admin_log") / $this->per_page);
    $logs = $this->DB->run("SELECT
      L.id,
      L.datetime, 
      L.adminckey, 
      L.operation,
      L.target,
      L.log,
      A.rank as adminrank
      FROM tbl_admin_log as L
      LEFT JOIN tbl_admin as A ON L.adminckey = A.ckey
      ORDER BY L.datetime DESC
      LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    $pm = new Player($this->container->get('settings')['statbus']);
    foreach ($logs as &$l){
      $l->admin = new \stdclass;
      $l->admin->ckey = $l->adminckey;
      $l->admin->rank = $l->adminrank;
      $l->admin = $pm->parsePlayer($l->admin);
      $l->class = '';
      $l->icon  = 'edit';
      switch($l->operation){
        case 'add admin':
          $l->class = 'success';
          $l->icon  = 'user-plus';
        break;
        case 'remove admin':
          $l->class = 'danger';
          $l->icon  = 'user-times';
        break;
        case 'change admin rank':
          $l->class = 'info';
          $l->icon  = 'user-tag';
        break;
        case 'add rank':
          $l->class = 'success';
          $l->icon  = 'plus-square';
        break;
        case 'remove rank':
          $l->class = 'warning';
          $l->icon  = 'minus-square';
        break;
        case 'change rank flags':
          $l->class = 'primary';
          $l->icon  = 'flag';
        break;
      }
      $l->operation = ucwords($l->operation);
    }
    return $this->view->render($response, 'info/admin_log.tpl',[
      'logs'   => $logs,
      'info'   => $this,
      'wide'   => true
    ]);
  }
}