<?php

namespace Statbus\Models;

class Player {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
  }

  public function parsePlayer(&$player) {

    $player->design = [
      'icon' => null,
      'backColor' => '#FFF',
      'foreCoor' => '#000'
    ];

    @$player->design = $this->settings['ranks'][$player->rank->rank];

    $player->label = "<span class='badge ml-1'";
    $player->label.= " data-toggle='tooltip' title='".$player->rank->rank."' ";
    $player->label.= "style='background: ".$player->design['backColor'].";";
    $player->label.= " color: ".$player->design['foreColor']."' title='".$player->rank->rank."'>";
    $player->label.= "<i class='fas fa-fw fa-".$player->design['icon']."'></i> ";
    $player->label.= "$player->ckey</span>";

    return $player;
  }
}