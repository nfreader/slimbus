<?php

namespace Statbus\Models;

class Death {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
  }

  public function parseDeath(&$death) {
    $death->server = $this->settings['servers'][array_search($death->port, array_column($this->settings['servers'], 'port'))]['name'];

    $death->class = '';
    if($death->lakey)   $death->class = "murder";
    if($death->suicide) $death->class = "suicide";

    if($death->special) $death->special = ucwords($death->special);

    $death->vitals = new \stdclass;
    $death->vitals->brute   = $death->brute; unset($death->brute);
    $death->vitals->brain   = $death->brain; unset($death->brain);
    $death->vitals->fire    = $death->fire; unset($death->fire);
    $death->vitals->oxy     = $death->oxy; unset($death->oxy);
    $death->vitals->tox     = $death->tox; unset($death->tox);
    $death->vitals->clone   = $death->clone; unset($death->clone);
    $death->vitals->stamina = $death->stamina; unset($death->stamina);

    $death->max = array_search(max((array) $death->vitals),(array) $death->vitals);
    $death->cause = "Natural causes";
    switch ($death->max){
      case 'brute':
        $death->cause      = "Blunt-Force Trauma";
        $death->last_line  = "as they were beaten to death";
      break;

      case 'brain':
        $death->cause      = "Crippling Brain Damage";
        $death->last_line  = "slurred out as they gave up on life";
      break;

      case 'fire':
        $death->cause      = "Severe Burns";
        $death->last_line  = "screamed in agony";
      break;

      case 'oxy':
        $death->cause      = "Suffocation";
        $death->last_line  = "with their dying breath";
      break;

      case 'tox':
        $death->cause      = "Poisoning";
        $death->last_line  = "twitching as toxins coursed through their system";
      break;

      case 'clone':
        $death->cause      = "Poor Cloning Technique";
        $death->last_line  = "scrawled into the floor where they died";
      break;

      case 'stamina':
        $death->cause      = "Exhaustion";
        $death->last_line  = "whispered in their final moments";
      break;
    }
    $death->map_url = str_replace(' ', '', $death->mapname);
    return $death;
  }
}