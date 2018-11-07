<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;


class PlayerController Extends Controller {

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->playerModel = new Player($this->container->get('settings')['statbus']);
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

  public function getPlayerByCkey($ckey){
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
      floor((G.minutes + L.minutes) / 60) AS hours
      FROM tbl_player
      LEFT JOIN tbl_connection_log ON tbl_connection_log.ckey = tbl_player.ckey
      LEFT JOIN tbl_role_time AS G ON G.ckey = tbl_player.ckey AND G.job = 'Ghost'
      LEFT JOIN tbl_role_time AS L ON L.ckey = tbl_player.ckey AND L.job = 'Living'
      LEFT JOIN tbl_admin ON tbl_player.ckey = tbl_admin.ckey
      WHERE tbl_player.ckey = ?", $ckey);
  }

   public function getRoleData($ckey) {
    return json_encode($this->DB->run("SELECT job, minutes
      FROM tbl_role_time
      WHERE ckey = ?
      AND tbl_role_time.job IN ('Assistant','Scientist','Shaft Miner','Station Engineer','Cyborg','Medical Doctor','Security Officer','Roboticist','Cargo Technician','Botanist','Chemist','AI','Cook','Atmospheric Technician','Janitor','Clown','Captain','Bartender','Head of Personnel','Quartermaster','Chaplain','Geneticist','Chief Engineer','Research Director','Mime','Lawyer','Detective','Chief Medical Officer','Head of Security','Virologist','Librarian','Warden')
      ORDER BY job ASC", $ckey));
  }

  public function getNamesFromDeaths($ckey) {
    return $this->DB->run("SELECT DISTINCT(`name`),
      count(id) AS times
      FROM ss13death
      WHERE byondkey = ?
      GROUP BY `name`
      HAVING times > 1
      ORDER BY times DESC
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
      FROM ss13player AS P
      LEFT JOIN ss13connection_log AS I ON I.ip = P.ip AND I.ckey != P.ckey
      LEFT JOIN ss13connection_log AS C ON C.computerid = P.computerid AND P.ckey != C.ckey
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

  public function gatherAdditionalData(&$player){
    $player->role_time = $this->getRoleData($player->ckey);
    $player->messages = (new MessageController($this->container))->getMessagesForCkey($player->ckey, TRUE);
    $player->names = $this->getNamesFromDeaths($player->ckey);
    $player->standing = (new BanController($this->container))->getPlayerStanding($player->ckey);
    $player->ips = $this->getIPs('ckey', $player->ckey);
    $player->cids = $this->getCIDs('ckey', $player->ckey);
    $player->alts = $this->findAlts($player->ckey);
    return $player;
  }
}