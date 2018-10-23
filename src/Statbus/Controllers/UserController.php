<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;
// use GuzzleHtpp\Guzzle;

class UserController Extends Controller {

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->playerModel = new Player($this->container->get('settings')['statbus']);

    if(isset($_SESSION['sb']['byond_ckey'])){
      $this->user = $this->getUser($_SESSION['sb']['byond_ckey']);
      $this->verifyAdminRank();
      $this->user = $this->playerModel->parsePlayer($this->user);
      $this->view->getEnvironment()->addGlobal('user', $this->user);
    }
  }

  public function getUser($ckey){
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

  public function verifyAdminRank() {
    if(empty($this->user->ckey)){
      $this->user->rank = 'Player';
      return;
    }
    $this->user->rank = $this->DB->row("SELECT tbl_admin.rank,
      tbl_admin_ranks.flags,
      tbl_admin_ranks.exclude_flags,
      tbl_admin_ranks.can_edit_flags
      FROM tbl_admin
      LEFT JOIN tbl_admin_ranks ON tbl_admin.rank = tbl_admin_ranks.rank
      WHERE tbl_admin.ckey = ?", $this->user->ckey);
    if(!$this->user->rank){
      $this->user->rank = 'Player';
      return;
    }

    $perms = $this->container->get('settings')['statbus']['perm_flags'];
    foreach($perms as $p => $b){
      if ($this->user->rank->flags & $b){
        $this->user->rank->permissions[] = $p;
      }
    }


    // $this->user->perms = new \stdclass;
    // $this->user->perms->flags = $this->user->rank->flags;
    // $this->user->perms->exclude_flags = $this->user->rank->exclude_flags;
    // $this->user->perms->can_edit_flags = $this->user->rank->can_edit_flags;
    // $this->user->perms->permissions = [];
    // foreach($this->user->perms->flags as $k => $v){
    //   if($tmp->perms->flags & $v){
    //     $tmp->perms->permissions[] = $k;
    //   }
    // }

  }

  public function fetchUser(){
    return $this->user;
  }
  
  public function canAccessTGDB(){
    if(empty($this->user->ckey)) return false;
    if(in_array('BAN', $this->user->rank->permissions)) return true;
    return false;
  }
}