<?php

namespace Statbus\Models;

class Stat {

  private $filters;

  public function __construct(){
    $this->filters = json_decode(file_get_contents(ROOTDIR."/src/conf/stat_filters.json"));
  }

  public function parseStat(&$stat, $collate = false){
    if (!$collate){
      return $this->singleParse($stat);
    } else {
      foreach($stat as &$s){
        $s = $this->singleParse($s);
      }
      $stat = $this->collate($stat);
      return $stat;
    }
  }

  public function singleParse(&$stat){
    @$stat->label = $this->filters->{$stat->key_name}->label;
    if(isset($stat->label->filter)) {
      $stat->json = str_replace($stat->label->filter, '', $stat->json);
    }
    $stat->data = json_decode($stat->json, TRUE)['data'];
    $stat = $this->specialCases($stat);
    $stat->output = $stat->data;
    switch($stat->key_type){
      case 'associative':

      break;

      case 'amount':
      break;

      case 'nested tally':
      break;

      case 'tally':
        $stat->total = array_sum($stat->data);
        $stat->output = arsort($stat->output);
      break;
    }
    return $stat;
  }

  public function collate(&$stat){
    $tmp = new \stdclass;
    $tmp->collated = TRUE;
    $tmp->key_name = $stat[0]->key_name;
    $tmp->key_type = $stat[0]->key_type;
    $tmp->version = $stat[0]->version;
    $tmp->first_date = $stat[0]->datetime;
    $tmp->last_date = end($stat)->datetime;
    $tmp->first_round = $stat[0]->round_id;
    $tmp->last_round = end($stat)->round_id;

    $tmp->rounds = [];
    $tmp->dates = [];
    $tmp->js = [];

    $a = new \DatePeriod(
      new \DateTime($stat[0]->datetime),
      new \DateInterval('P1D'),
      new \DateTime(end($stat)->datetime)
    );

    foreach ($a as $key => $value) {
      $tmp->dates[$value->format('Y-m-d')] = 0;     
    }

    switch($tmp->key_type){
      case 'associative':

      break;

      case 'amount':
        $tmp->output = 0;
        foreach($stat as $s){
          $tmp->output += $s->data;
          $tmp->rounds[$s->round_id] = $s->data;
          $tmp->dates[(new \dateTime($s->datetime))->format('Y-m-d')] += $s->data;
        }
        foreach ($tmp->dates as $k => $v){
          $tmp->js[] = [
            'x' => $k,
            'y' => $v
          ];
        }
      break;

      case 'nested tally':
      break;

      case 'tally':
      $data = [];
      foreach($stat as $s){
        $tmp->rounds[] = $s->round_id;
        $data = array_merge($data, $s->data);
        arsort($data);
      }
      $tmp->output = $data;
      break;
    }
    return $tmp;
  }

  public function specialCases(&$stat){
    switch($stat->key_name){
      case 'commendation':
        foreach($stat->data as &$d){
          $d['id'] = strtoupper(substr(hash('sha512', $d['commendee'].$d['reason']), 0,6));
        }
      break;

      case 'testmerged_prs':
        $stat->data = array_map("unserialize", array_unique(array_map("serialize", $stat->data)));
      break;
    }

    return $stat;
  }

}