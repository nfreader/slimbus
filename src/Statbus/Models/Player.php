<?php

namespace Statbus\Models;

class Player {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
  }

  public function parsePlayer(&$player, $extended=false) {
    if(is_string($player->rank)) {
      $rank = $player->rank;
    } elseif (is_object($player->rank)) {
      $rank = $player->rank->rank;
    } else {
      $rank = 'Player';
      $player->rank = 'Player';
    }

    $player->design = [
      'icon' => 'user',
      'backColor' => '#eee',
      'foreColor' => '#000'
    ];
    if (isset($this->settings['ranks'][$rank])) {
      $player->design = $this->settings['ranks'][$rank];
    }
    $player->label = "<span class='badge ml-1'";
    $player->label.= " data-toggle='tooltip' title='".$rank."' ";
    $player->label.= "style='background: ".$player->design['backColor'].";";
    $player->label.= " color: ".$player->design['foreColor']."' title='".$rank."'>";
    $player->label.= "<i class='fas fa-".$player->design['icon']."'></i> ";
    $player->label.= "$player->ckey</span>";

    @$player->ip_real = long2ip($player->ip);

    return $player;
  }
}