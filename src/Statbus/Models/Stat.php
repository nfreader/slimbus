<?php

namespace Statbus\Models;

class Stat {

  private $filters;

  public function __construct(){
    $this->filters = json_decode(file_get_contents(ROOTDIR."/src/conf/stat_filters.json"));
  }

  public function parseStat(&$stat, $aggregate=false){
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