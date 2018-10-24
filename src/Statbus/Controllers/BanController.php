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
    $standing->ban_list = [];

    $bans = $this->DB->run("SELECT bantype,
      id
      FROM ss13ban
      WHERE ckey = ?
      AND (ss13ban.expiration_time > NOW()
      OR ss13ban.unbanned != 1
      OR (ss13ban.duration = -1
      AND ss13ban.unbanned IS NULL))
      ORDER BY bantype, id DESC;", $ckey);
    if(!$bans){
      $standing->class = 'success';
      $standing->text  = 'Not Banned';
      $id = false;
      return $standing;
    }
    $ban_list = [];
    foreach ($bans as $b){
      if(isset($standing->ban_list[$b->bantype])){
        continue;
      } else {
        $standing->ban_list[$b->bantype] = $b->id;
      }
    }
    $bl = array_keys($standing->ban_list);
    if(in_array('JOB_TEMPBAN', $bl)){
      $standing->class = 'danger';
      $standing->text  = 'Job Tempbanned';
      $standing->id    = $standing->ban_list['TEMPBAN'];
    }
    if(in_array('TEMPBAN', $bl)){
      $standing->class = 'danger';
      $standing->text  = 'Temporarily Banned';
      $standing->id    = $standing->ban_list['JOB_TEMPBAN'];
    }
    if(in_array('ADMIN_TEMPBAN', $bl)){
      $standing->class = 'danger';
      $standing->text  = 'Temporarily Admin Banned';
      $standing->id    = $standing->ban_list['ADMIN_TEMPBAN'];
    }
    if(in_array('JOB_PERMABAN', $bl)){
      $standing->class = 'perma';
      $standing->text  = 'Permanently Job Banned';
      $standing->id    = $standing->ban_list['JOB_PERMABAN'];
    }
    if(in_array('ADMIN_PERMABAN', $bl)){
      $standing->class = 'perma';
      $standing->text  = 'Permanently Admin Banned';
      $standing->id    = $standing->ban_list['ADMIN_PERMABAN'];
    }
    if(in_array('PERMABAN', $bl)){
      $standing->class = 'perma';
      $standing->text  = 'Permanently Banned';
      $standing->id    = $standing->ban_list['PERMABAN'];
    }
    return $standing;
  }
}