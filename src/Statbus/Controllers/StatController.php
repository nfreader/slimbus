<?php

namespace Statbus\Controllers;

use ParagonIE\EasyDB\EasyDB;
use Statbus\Models\Stat as Stat;

class StatController {

  protected $DB;

  public function __construct(EasyDB $db) {
    $this->DB = $db;
    $this->statModel = (new Stat());
  }

  public function getRoundStat($round, $stat, $json = false) {
    $stat = $this->DB->row("SELECT * FROM tbl_feedback WHERE round_id = ? AND key_name = ?", $round, $stat);
    if(!$stat){
      return false;
    }
    if($json) {
      return $stat->json;
    }
    return $this->statModel->parseStat($stat);
  }

  public function getStatsForRound($round, array $stats = null) {
    if(is_array($stats)){
      $and = "((key_name = '";
      $and.= implode("') OR (key_name = '", $stats);
      $and.= "'))";
      $stats = $this->DB->run("SELECT * FROM tbl_feedback
        WHERE $and
        AND round_id = ?", $round);
      $tmp = [];
      foreach ($stats as &$stat){
        $stat = $this->statModel->parseStat($stat);
        $tmp[$stat->key_name] = $stat;
      }
      $stats = $tmp;
      return $stats;
    } else {
      $stats = $this->DB->run("SELECT key_name FROM tbl_feedback WHERE round_id = ? ORDER BY key_name ASC", $round);
      return $stats;
    } 
  }
}