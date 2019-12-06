<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;
use Statbus\Controllers\UserController as User;

class PlayerController Extends Controller {

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->sb = $this->container->get('settings')['statbus'];
    $this->playerModel = new Player($this->sb);
    $dbConn = $this->container->get('settings')['database']['alt'];
    $this->alt_db = (new DBController($dbConn))->db;
  }

  public function getPlayer($request, $response, $args) {
    $ckey = filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $player = $this->getPlayerByCkey($ckey);
    if (!$player->ckey) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Ckey not found",
        'code'    => 404,
      ]);
    }
    $player = $this->gatherAdditionalData($player);
    $player = $this->playerModel->parsePlayer($player);
    return $this->view->render($response, 'player/single.tpl',[
      'player' => $player
    ]);
  }

  public function getPlayerPublic($request, $response, $args) {
    $player = $this->getPlayerByCkey(filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH),TRUE);
    if (!$player->ckey) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Ckey not found",
        'code'    => 404,
      ]);
    }
    $player->achievements = (new AchievementController($this->container))->getPlayerAchievements($player->ckey);
    return $this->view->render($response, 'player/public.tpl',[
      'player' => $player
    ]);
  }

  public function getPlayerByCkey($ckey, $public = false){
    if($public){
      return $this->DB->row("SELECT tbl_player.ckey
        FROM tbl_player
        WHERE tbl_player.ckey = ?", $ckey);
    }
    return $this->DB->row("SELECT tbl_player.ckey,
      tbl_player.firstseen,
      tbl_player.firstseen_round_id,
      tbl_player.lastseen,
      tbl_player.lastseen_round_id,
      tbl_player.ip,
      tbl_player.computerid,
      tbl_admin.rank,
      tbl_player.accountjoindate,
      tbl_player.flags,
      count(DISTINCT tbl_connection_log.id) AS connections,
      G.minutes as ghost,
      L.minutes as living,
      floor((G.minutes + L.minutes) / 60) AS hours,
      DATEDIFF(CURDATE(),tbl_player.lastseen) AS days
      FROM tbl_player
      LEFT JOIN tbl_connection_log ON tbl_connection_log.ckey = tbl_player.ckey
      LEFT JOIN tbl_role_time AS G ON G.ckey = tbl_player.ckey AND G.job = 'Ghost'
      LEFT JOIN tbl_role_time AS L ON L.ckey = tbl_player.ckey AND L.job = 'Living'
      LEFT JOIN tbl_admin ON tbl_player.ckey = tbl_admin.ckey
      WHERE tbl_player.ckey = ?", $ckey);
  }


  public function getPlayerRoleTime($request, $response, $args) {
    if(isset($args['ckey'])){
    $ckey = filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    } else {
      $ckey = $this->container->user->ckey;
    }
    $player = $this->getPlayerByCkey($ckey);
    if (!$player->ckey) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Ckey not found",
        'code'    => 404,
      ]);
    }
    // $player = $this->gatherAdditionalData($player);
    $p = $request->getQueryParams();
    $start = null;
    $end = null;
    if(isset($p['start']) && isset($p['end'])){
      $start = filter_var($p['start'], FILTER_SANITIZE_STRING, 
       FILTER_FLAG_STRIP_HIGH);
      $end = filter_var($p['end'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    $jobs = "('".implode("','",$this->sb['jobs'])."')";
    $minmax = $this->DB->row("SELECT 
      min(STR_TO_DATE(R.datetime, '%Y-%m-%d')) AS min,
      max(STR_TO_DATE(R.datetime, '%Y-%m-%d')) AS max
      FROM tbl_role_time_log AS R
      WHERE R.datetime != '0000-00-00 00:00:00'
      AND R.job IN $jobs
      AND ckey = ?;", $ckey);
    if(!$start) {
      $start = $minmax->min;
      $end = $minmax->max;
    } else {
      $startDate = new \dateTime($start);
      $start = $startDate->format('Y-m-d');
      $endDate = new \dateTime($end);
      $end = $endDate->format('Y-m-d');
    }
    $player->role_time = $this->getRoleData($player->ckey, $start, $end, $jobs);
    $player = $this->playerModel->parsePlayer($player);
    return $this->view->render($response, 'player/roles.tpl',[
      'player' => $player,
      'start'  => $start,
      'end'    => $end,
      'min'    => $minmax->min,
      'max'    => $minmax->max
    ]);
  }

  public function getPlayerByIP(int $IP){
    return $this->DB->row("SELECT tbl_player.ckey,
      tbl_player.firstseen,
      tbl_player.firstseen_round_id,
      tbl_player.lastseen,
      tbl_player.lastseen_round_id,
      tbl_player.ip,
      tbl_player.computerid,
      tbl_admin.rank,
      tbl_player.accountjoindate,
      tbl_player.flags,
      count(DISTINCT tbl_connection_log.id) AS connections,
      G.minutes as ghost,
      L.minutes as living,
      floor((G.minutes + L.minutes) / 60) AS hours,
      DATEDIFF(CURDATE(),tbl_player.lastseen) AS days
      FROM tbl_player
      LEFT JOIN tbl_connection_log ON tbl_connection_log.ckey = tbl_player.ckey
      LEFT JOIN tbl_role_time AS G ON G.ckey = tbl_player.ckey AND G.job = 'Ghost'
      LEFT JOIN tbl_role_time AS L ON L.ckey = tbl_player.ckey AND L.job = 'Living'
      LEFT JOIN tbl_admin ON tbl_player.ckey = tbl_admin.ckey
      WHERE tbl_player.ip = ?", $IP);
  }

   public function getRoleData($ckey, $start = null, $end = null, $jobs) {
    return json_encode($this->DB->run("SELECT job, SUM(delta) AS minutes
      FROM tbl_role_time_log
      WHERE ckey = ?
      AND tbl_role_time_log.job IN $jobs
      AND tbl_role_time_log.datetime BETWEEN ? AND ?
      GROUP BY job
      ORDER BY job ASC", $ckey, $start, $end));
  }

  public function getPlayerNames($ckey) {
    $names['deaths'] = $this->getPlayerNamesFromDeath($ckey);
    $names['manifest'] = $this->getPlayerNamesFromManifest($ckey);
    return $names;
  }

  public function getPlayerNamesFromDeath($ckey) {
    return $this->DB->run("SELECT DISTINCT(`name`),
      count(id) AS `times`
      FROM tbl_death
      WHERE byondkey = ?
      GROUP BY `name`
      HAVING times > 1
      ORDER BY `times` DESC
      LIMIT 0,5;", $ckey);
  }

  public function getPlayerNamesFromManifest($ckey) {
    if(!$this->alt_db) return false;
    return $this->alt_db->run("SELECT DISTINCT(`name`),
      count(`name`) AS `times`
      FROM manifest
      WHERE ckey = ?
      GROUP BY `name`
      HAVING `times` > 1
      ORDER BY `times` DESC
      LIMIT 0,5;", $ckey);
  }

  public function getIPs($key, $value){
    $ips = $this->DB->run("SELECT count(id) as connections, ip
      FROM tbl_connection_log
      WHERE $key = ? GROUP BY ip;", $value);
    foreach($ips as &$ip){
      $ip->real = long2ip($ip->ip);
    }
    return $ips;
  }

  public function getCIDs($key, $value){
    return $this->DB->run("SELECT count(id) as connections, computerid
      FROM tbl_connection_log
      WHERE $key = ? GROUP BY computerid;", $value);
  }

  public function findAlts($ckey){
    $alts = $this->DB->run("SELECT
      I.ckey AS ip_alts,
      C.ckey AS cid_alts
      FROM tbl_player AS P
      LEFT JOIN tbl_connection_log AS I ON I.ip = P.ip AND I.ckey != P.ckey
      LEFT JOIN tbl_connection_log AS C ON C.computerid = P.computerid AND P.ckey != C.ckey
      WHERE P.ckey = ?
      GROUP BY ip_alts, cid_alts;", $ckey);
    foreach ($alts as $a){
      $return['ip_alts'][] = $a->ip_alts;
      $return['cid_alts'][] = $a->cid_alts;
    }
    $return['ip_alts'] = array_filter(array_unique($return['ip_alts']));
    $return['cid_alts'] = array_filter(array_unique($return['cid_alts']));
    $return['alts'] = count($return['ip_alts']) + count($return['cid_alts']);
    return $return;
  }

  public function countRounds($ckey){
    return $this->DB->cell("SELECT count(tbl_round.id) FROM tbl_connection_log
      LEFT JOIN tbl_round ON tbl_connection_log.round_id = tbl_round.id
      WHERE tbl_connection_log.ckey = ?
      AND tbl_round.shutdown_datetime IS NOT NULL", $ckey);
  }

  public function countMessages($ckey){
    return $this->DB->cell("SELECT count(M.id) FROM tbl_messages M
      WHERE M.deleted = 0
      AND (M.expire_timestamp > NOW() OR M.expire_timestamp IS NULL)
      AND M.targetckey = ?", $ckey);
  }

  public function gatherAdditionalData(&$player){
    $player->names = $this->getPlayerNames($player->ckey);
    $player->standing = (new BanController($this->container))->getPlayerStanding($player->ckey);
    $player->ips = $this->getIPs('ckey', $player->ckey);
    $player->cids = $this->getCIDs('ckey', $player->ckey);
    $player->alts = $this->findAlts($player->ckey);
    $player->roundCount = $this->countRounds($player->ckey);
    $player->messageCount = $this->countMessages($player->ckey);
    return $player;
  }

  public function getLastWords($ckey){
    return $this->DB->run("SELECT last_words, id FROM tbl_death WHERE byondkey = ? AND last_words IS NOT NULL AND last_words != '';", $ckey);
  }

  public function findCkeys($request, $response, $args){
    $args = $request->getQueryParams();
    $ckey = filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    if ($ckey){
      $results = $this->DB->run("SELECT tbl_player.ckey FROM tbl_player
        WHERE tbl_player.ckey LIKE ?
        ORDER BY lastseen DESC
        LIMIT 0, 15", '%'.$this->DB->escapeLikeValue($ckey).'%');
      return $response->withJson($results);
    }
  }

  public function getPlayerMessages($request, $response, $args) {
    $page = 1;
    if(isset($args['page'])) {
      $page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    if(isset($args['ckey'])){
    $ckey = filter_var($args['ckey'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    }
    $player = $this->getPlayerByCkey($ckey);
    if (!$player->ckey) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Ckey not found",
        'code'    => 404,
      ]);
    }
    $mc = new MessageController($this->container);
    $messages = $mc->getMessagesForCkey($player->ckey, FALSE, $page);
    $breadcrumbs[$player->ckey] = $this->router->pathFor('player.single',['ckey'=>$player->ckey]);
    $breadcrumbs["Messages"] = $this->router->pathFor('player.messages',['ckey'=>$player->ckey]);
    return $this->view->render($response, 'messages/player.tpl',[
      'player' => $player,
      'messages'  => $messages,
      'message' => $mc,
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function getMyMessages($request, $response, $args) {
    $page = 1;
    if(isset($args['page'])) {
      $page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $player = $this->getPlayerByCkey($this->container->user->ckey);
    if (!$player->ckey) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Ckey not found",
        'code'    => 404,
      ]);
    }
    $mc = new MessageController($this->container);
    $messages = $mc->getMessagesForCkey($player->ckey, TRUE, $page);
    $mc->url = $this->router->pathFor('me.messages');
    $breadcrumbs[$player->ckey] = $this->router->pathFor('me');
    $breadcrumbs["Messages"] = $this->router->pathFor('me.messages');
    return $this->view->render($response, 'messages/player.tpl',[
      'player' => $player,
      'messages'  => $messages,
      'message' => $mc,
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function name2ckey($request, $response, $args){
    if($request->isPost()){
      $name = filter_var($request->getParam('name'), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      if ($name){
        $results = $this->DB->run("SELECT count(D.id) AS count,
          D.byondkey,
          D.name
          FROM tbl_death D
          WHERE D.name LIKE ?
          GROUP BY `name`
          LIMIT 0, 15", '%'.$this->DB->escapeLikeValue($name).'%');
      }
    }
    return $this->view->render($response, 'tgdb/name2ckey.tpl',[
      'results' => $results,
      'name' => $name
    ]);
  }
}