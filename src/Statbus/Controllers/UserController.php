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
  
  public $user = false;
  private $skipRankVerify = false;

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->playerModel = new Player($this->container->get('settings')['statbus']);
    $this->PC = new PlayerController($this->container);
    $this->settings = $this->container->get('settings');

    if(isset($_SESSION['sb']['byond_ckey']) && $this->settings['statbus']['auth']['remote_auth']){
      $this->user = $this->PC->getPlayerByCkey($_SESSION['sb']['byond_ckey']);
    } elseif ($this->settings['statbus']['ip_auth']){
      $this->user = $this->PC->getPlayerByIP(ip2long($_SERVER['REMOTE_ADDR']));
      if($this->user->days > $this->settings['statbus']['ip_auth_days']){
        //Skip admin rank verification. 
        $this->skipRankVerify = true;
      }
    }
    if($this->user){
      $this->verifyAdminRank($this->skipRankVerify);
      $this->user = $this->playerModel->parsePlayer($this->user);
      $this->view->getEnvironment()->addGlobal('user', $this->user);
    }
  }

  public function verifyAdminRank($skip = false) {
    if(empty($this->user->ckey) || $skip){
      $this->user->rank = 'Player';
      return;
    }
    $this->user->rank = $this->DB->row("SELECT tbl_admin.rank,
      tbl_admin.feedback,
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

  public function getCkey(){
    return $this->user->ckey;
  }
  
  public function canAccessTGDB(){
    if(empty($this->user->ckey)) return false;
    if(is_string($this->user->rank) && 'Player' === $this->user->rank) return false;
    if(isset($this->user->rank->permissions) && in_array('BAN', $this->user->rank->permissions)) {
      $this->user->canAccessTGDB = true;
      return true;
    }
    return false;
  }

   public function me($request, $response, $args) {
    $lastWords = $this->PC->getLastWords($this->user->ckey);
    return $this->view->render($response, 'me/index.tpl',[
      'lastWords' => $lastWords
    ]);
  }

   public function addFeedback($request, $response, $args) {
    $feedback = filter_var($request->getParam('feedback'), FILTER_VALIDATE_URL);
    if($feedback){
      try{
        $this->DB->update('tbl_admin',[
          'feedback' => $feedback
        ],[
          'ckey' => $this->user->ckey
        ]);
        (new StatbusController($this->container))->submitToAuditLog('FBL', "Updated feedback link to '$feedback'");
        $this->user->rank->feedback = $feedback;
      } catch (Exception $e){
        return $this->view->render($response, 'base/error.tpl',[
          'message'  => $e->getMessage(),
          'code'     => 500
        ]);
      }
    }
    if(FALSE === $request->getAttribute('csrf_status')){
      return $this->view->render($response, 'base/error.tpl',[
        'message'  => "CSRF failure. This action is denied.",
        'code'     => 403,
        'link'     => $url,
        'linkText' => 'Back'
      ]);
    }
    return $this->view->render($response, 'tgdb/feedback.tpl',[
      'feedback' => $this->user->rank->feedback
    ]);
  }
}