<?php

namespace Statbus\Models;

class Stat {

  public function parseStat(&$stat, $aggregate=false){
    $stat->label = new \stdclass;
    $stat->label->key    = "Key";
    $stat->label->value  = "Value";
    $stat->label->total  = "Total";
    $stat->label->splain = FALSE;
    $stat->filter = false;
    $stat->special = false;
    $stat = $this->specialCases($stat);
    if($stat->filter) {
      $stat->json = str_replace($stat->filter, '', $stat->json);
    }
    if(!isset($stat->data)){
      $stat->data = json_decode($stat->json, TRUE)['data'];
    }
    if('tally' === $stat->key_type){
      arsort($stat->data);
      $stat->total = array_sum($stat->data);
    } elseif(!$aggregate && 'text' == $stat->key_type && 1 === count($stat->data)){
      $stat->data = $stat->data[0];
    }
    return $stat;
  }

  public function specialCases(&$stat) {
    switch($stat->key_name){
      default:
      break;

       case 'admin_verb':
         $stat->label->key   = "Verb";
         $stat->label->value = "Times Used";
       break;

       case 'antagonists':
         $stat->extra = new stdclass;
         $stat->extra->success = 0;
         $stat->extra->fail = 0;
         $stat->data = json_decode($stat->json)->data;
         foreach ($stat->data as $data){
          if(isset($data->objectives)){
            foreach($data->objectives as $o){
              if(isset($o->result)){
                if('SUCCESS' === $o->result){
                  $stat->extra->success++;
                } else {
                  $stat->extra->fail++;
                }
              }
            }
          }
         }
         unset($stat->data);
         if(0 === $stat->extra->fail && 0 === $stat->extra->success) unset($stat->extra);
       break;

      case 'cargo_imports':
        $stat->label->key = "Crate";
        $stat->label->value = "Number Ordered";
        $stat->label->value2 = "Cost";
      break;

      case 'chemical_reaction':
        $stat->label->key   = "Chemical Name";
        $stat->label->value = "Units Produced";
      break;

      case 'export_sold_cost':
        $stat->label->value2 = 'Item Name';
        $stat->label->value = 'Number Sold';
        $stat->label->key = 'Credits/Unit';
      break;

      case 'explosion':
        $stat->special = TRUE;
      break;

      case 'food_harvested':
        $stat->label->key = "Crop";
        $stat->label->value = "Units Harvested";
      break;

      case 'gun_fired':
        $stat->filter = '/obj/item/gun/';
        $stat->label->splain = "$stat->filter removed for readability purposes";
        $stat->label->key = "Weapon";
        $stat->label->value = "Shots Fired";
      break;

      case 'item_used_for_combat':
        $stat->label->key = "Weapon";
        $stat->label->value = "Times Used";
        $stat->label->value2 = "Damage";
        $stat->filter = "/obj/item/";
        $stat->splain = "$stat->filter removed for readability purposes";
      break;

      case 'mobs_killed_mining':
        $stat->filter = '/mob/living/simple_animal/hostile/asteroid/';
        $stat->label->splain = "$stat->filter removed for readability purposes";
        $stat->label->key = "Mob Name";
        $stat->label->value = "Mobs Killed";
      break;

      case 'nuclear_challenge_mode':
        $stat->data = TRUE;
      break;

      case 'ore_mined':
        $stat->filter = '/obj/item/stack/ore/';
        $stat->label->splain = "$stat->filter removed for readability purposes";
        $stat->label->key = "Ore Name";
        $stat->label->value = "Units Mined";
      break;

      case 'played_url':
        $stat->data = json_decode($stat->json,TRUE);
        $stat->data = $stat->data['data'];
        $tmp = [];
        // var_dump($stat->data);
        foreach($stat->data as $a => $u){
          if(isset($tmp[$a])){
            $tmp[$a] = array_merge($tmp[$a], $u);
          } else {
            $tmp[$a] = $u;
          }
        }
        
        $stat->data = $tmp;
        $stat->label->key = "URL Played";
        $stat->label->value = "Times Played";
        $stat->label->value2 = "Played By";
        $stat->special = TRUE;
      break;

      case 'radio_usage':
        $stat->label->key = "Channel";
        $stat->label->value = "Messages Transmitted";
      break;

      case 'religion_book':
        $stat->label->splain = "The crew dilligently read";

      break;

      case 'religion_deity':
        $stat->label->splain = "The crew worshipped";

      break;

      case 'religion_name':
        $stat->label->splain = "The crew practiced";
      break;

      case 'roundend_nukedisk':
        $stat->label->splain = "At the end of the round, the nuclear authentication disk was located here";
        $stat->special = TRUE;
      break;

      case 'security_level_changes': 
        $stat->json = str_replace('"0"', '"Security Level Green"', $stat->json);
        $stat->json = str_replace('"1"', '"Security Level Blue"', $stat->json);
        $stat->json = str_replace('"2"', '"Security Level Red"', $stat->json);
        $stat->json = str_replace('"3"', '"Security Level Delta"', $stat->json);
        $stat->label->key = "Security Level";
        $stat->label->value = "Times Escalated";

      break;

      case 'shuttle_purchase':
        $stat->label->splain = "The crew purchased and evacuated aboard";

      break;

      case 'shuttle_reason':
        $stat->label->splain = "The emergency shuttle has been called!";
        $stat->special = TRUE;
      break;

      case 'station_renames':
        $stat->label->splain = "These are the tales of the crew of the";
        $stat->special = TRUE;
      break;

      case 'time_dilation_current':
        if($stat->version < 2) return $stat;
        $data = json_decode($stat->json)->data;
        $stat->data = [];
        foreach ($data as $k => $v){
          foreach ($v as $a => $b){
            $b->date = $a;
            $stat->data[] = $b;
          }
        }
        $stat->special = TRUE;
      break;

      case 'traitor_uplink_items_bought':
        $stat->label->key = "TC Cost";
        $stat->label->value = "Times Purchased";
        $stat->label->value2 = "Name";
      break;

      case 'traumas':
        $stat->filter = [
          '/datum/brain_trauma/mild/',
          '/datum/brain_trauma/severe/',
          '/datum/brain_trauma/'
        ];
        $stat->splain = "Trauma paths removed for readability purposes";
      break;
    }
    return $stat;
  }
}