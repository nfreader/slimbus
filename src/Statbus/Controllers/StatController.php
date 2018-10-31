<?php

namespace Statbus\Controllers;

use ParagonIE\EasyDB\EasyDB;
use Statbus\Models\Stat as Stat;

class StatController {

  protected $DB;

  public function __construct(EasyDB $db) {
    $this->DB = $db;
  }

  public function getRoundStat($round, $stat, $json = false) {
    $stat = $this->DB->row("SELECT * FROM tbl_feedback WHERE round_id = ? AND key_name = ?", $round, $stat);
    if(!$stat){
      return false;
    }
    if($json) {
      return $stat->json;
    }
    return (new Stat())->parseStat($stat);
  }

  public function getStatsForRound($round) {
    $stats = $this->DB->run("SELECT key_name FROM tbl_feedback WHERE round_id = ? ORDER BY key_name ASC", $round);
    return $stats;
  }
}