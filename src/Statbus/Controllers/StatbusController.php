<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;
use Statbus\Controllers\MessageController as MessageController;

class StatbusController extends Controller {


  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->guzzle = $this->container->get('guzzle');
    $this->user = $this->container->get('user')->user;
  }

  public function index($request, $response, $args) {
    return $this->view->render($response, 'index.tpl',[
      'numbers' => $this->getBigNumbers(),
      'poly'    => $this->getPolyLine(),
    ]);
  }

  public function getBigNumbers(){
    $numbers = new \stdclass;
    $numbers->playtime = number_format($this->DB->row("SELECT sum(tbl_role_time.minutes) AS minutes FROM tbl_role_time WHERE tbl_role_time.job = 'Living';")->minutes);
    $numbers->deaths = number_format($this->DB->cell("SELECT count(id) as deaths FROM tbl_death;")+rand(-15,15));//fuzzed
    $numbers->rounds = number_format($this->DB->cell("SELECT count(id) as rounds FROM tbl_round;"));
    $numbers->books = number_format($this->DB->cell("SELECT count(tbl_library.id) FROM tbl_library WHERE tbl_library.content != ''
      AND (tbl_library.deleted IS NULL OR tbl_library.deleted = 0)"));
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
      IF(A.rank IS NULL, 'Player', A.rank) as adminrank
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

  public function tgdbIndex() {
    //This method exists solely to scaffold the tgdb index page
    $memos = (new MessageController($this->container))->getAdminMemos();
    return $this->view->render($this->response, 'tgdb/index.tpl',[
      'memos' => $memos
    ]);
  }

  public function getPolyLine() {
    if($this->container->get('settings')['statbus']['remote_log_src']){
      $server = pick('sybil,terry');
      $poly = $this->guzzle->request('GET','https://tgstation13.org/parsed-logs/'.$server.'/data/npc_saves/Poly.json');
      $poly = json_decode((string) $poly->getBody(), TRUE);
      return pick($poly['phrases']);
    } else {
      return false;
    }
  }

  public function popGraph(){
    $query = "SELECT
    FLOOR(AVG(admincount)) AS admins,
    FLOOR(AVG(playercount)) AS players,
    server_port,
    HOUR(`time`) AS `hour`,
    DATE_FORMAT(`time`, '%Y-%m-%e %H:00:00') as `date`,
    count(round_id) AS rounds
    FROM ss13legacy_population
    WHERE `time` > DATE_FORMAT(CURDATE(), '%Y-%m-01') - INTERVAL 2 YEAR
    GROUP BY HOUR (`time`), DAY(`TIME`), MONTH(`TIME`), YEAR(`TIME`), server_port
    ORDER BY `time` DESC;";
    $hash = hash('sha512',$query);
    if(file_exists(ROOTDIR."/tmp/db/$hash")){
      $data = file_get_contents(ROOTDIR."/tmp/$hash");
      $data = json_decode($data);
      if($data->timestamp > time()){
        return $this->view->render($this->response, 'info/heatmap.tpl',[
          'data'      => json_encode($data->data),
          'fromCache' => TRUE,
          'hash'      => $hash
        ]);
      }
    }
    $data = $this->DB->run($query);

    $tmp = new \stdclass;
    $tmp->timestamp = time() + 86400;
    $tmp->data = $data;
    $tmp = json_encode($tmp);
    $file = fopen(ROOTDIR."/tmp/db/$hash", "w+");
    fwrite($file, $tmp);
    fclose($file);
    return $this->view->render($this->response, 'info/heatmap.tpl',[
      'data' => json_encode($data),
      'hash' => $hash
    ]);
  }

  public function submitToAuditLog($action, $text){
    //Check if the audit log exists
    try {
      $this->DB->run("SELECT 1 FROM tbl_external_activity LIMIT 1");
    } catch (\PDOException $e){
      return false;
    }
    $this->DB->insert('tbl_external_activity',[
      'action' => $action,
      'text'   => $text,
      'ckey'   => ($this->user->ckey) ? $this->user->ckey : null,
      'ip'     => ip2long($_SERVER['REMOTE_ADDR'])
    ]);
  }
}