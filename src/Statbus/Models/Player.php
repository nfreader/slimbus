<?php

namespace Statbus\Models;

class Player {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
  }

  public function parsePlayer(&$player) {

    if(is_string($player->rank)) {
      $rank = $player->rank;
    } else {
      $rank = $player->rank->rank;
    }

    $player->design = [
      'icon' => null,
      'backColor' => '#FFF',
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

    return $player;
  }
}