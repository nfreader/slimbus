<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Controllers\PlayerController as PlayerController;
use Statbus\Models\Player as Player;
// use GuzzleHtpp\Guzzle;

class UserController Extends Controller {

  //This controller is STRICTLY reserved for the current logged in user ONLY
  //and details about them.

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->playerModel = new Player($this->container->get('settings')['statbus']);
    $this->PC = new PlayerController($this->container);

    if(isset($_SESSION['sb']['byond_ckey'])){
      $this->user = $this->getUser($_SESSION['sb']['byond_ckey']);
      $this->verifyAdminRank();
      $this->user = $this->playerModel->parsePlayer($this->user);
      $this->view->getEnvironment()->addGlobal('user', $this->user);
    }
  }

  public function getUser($ckey){
    return $this->PC->getPlayerByCkey($ckey);
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
    $this->canAccessTGDB();
  }

  public function fetchUser(){
    return $this->user;
  }
  
  public function canAccessTGDB(){
    if(empty($this->user->ckey)) return false;
    if(in_array('BAN', $this->user->rank->permissions)) {
      $this->user->canAccessTGDB = true;
      return true;
    }
    return false;
  }

   public function me($request, $response, $args) {
    $roleData = $this->PC->getRoleData($this->user->ckey);
    return $this->view->render($response, 'me/index.tpl',[
      'roleData' => $roleData
    ]);
  }
}