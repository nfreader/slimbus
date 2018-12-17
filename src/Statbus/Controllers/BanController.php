<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;

class BanController extends Controller {
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
  }

  public function getPlayerStanding($ckey){
    $standing = new \stdclass;
    $standing->bans = $this->DB->run("
      SELECT B.role, 
      B.id,
      B.expiration_time
      FROM tbl_ban AS B
      WHERE ckey = ?
      AND ((B.expiration_time > NOW() AND B.unbanned_ckey IS NULL)
      OR (B.expiration_time IS NULL AND B.unbanned_ckey IS NULL))", $ckey);
    foreach($standing->bans as &$b){
      //Loop through all the active bans we found
      //If there's no expiration time set, flag it as perma
      $b->perm = (isset($b->expiration_time)) ? FALSE : TRUE;
      //If we find a ban with a perma flag set, and if it's a server role,
      //exit the loop. We got what we needed.
      if ($b->perm && 'Server' === $b->role){
        $standing->class = "perma";
        $standing->text = "Permabanned";
        continue;
      } else {
        $standing->class = "danger";
        $standing->text = "Active Bans";
      }
    }
    if(!$standing->bans){
      $standing->class = 'success';
      $standing->text  = 'Not Banned';
      return $standing;
    }
    return $standing;
  }
}