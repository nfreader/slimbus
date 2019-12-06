<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Player as Player;

class AchievementController Extends Controller {

  public $cheevoMap = [];
  public $cheevos = [];

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->mapAchievements();
  }

  public function mapAchievements(){
    $this->cheevoMap = $this->DB->run("SELECT achievement_key AS `key`,
    achievement_version AS version,
    achievement_type AS type
    FROM tbl_achievement_metadata");
  }

  public function getPlayerAchievements($ckey) {
    $cheevos = $this->DB->run("SELECT 
      achievement_key AS `key`,
      value
      FROM tbl_achievements
      WHERE ckey = ?", $ckey);
    $this->mapMetadata($cheevos);
    return $this->cheevos;
  }

  public function mapMetadata($cheevos = null){
    if ($cheevos){
      $this->cheevos = $cheevos;
    }
    foreach($this->cheevos as &$cheevo){
      foreach($this->cheevoMap as $meta){
        if($cheevo->key === $meta->key){
          $cheevo->type = $meta->type;
        }
      }
    }
    usort($this->cheevos, function($a, $b) {return strcmp($a->type, $b->type);});
  }
}