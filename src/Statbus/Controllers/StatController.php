<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Stat as Stat;

class StatController Extends Controller {

  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
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
      if (!$stats) return false;
      foreach($stats as $s){
        $tmp[] = $s->key_name;
      }
      $stats = array_flip($tmp);
      return $stats;
    } 
  }

  public function list($request, $response, $args){
    $stats = $this->DB->run("SELECT R.key_name, R.key_type, R.version, count(R.round_id) AS rounds FROM tbl_feedback R GROUP BY R.key_name, R.version ORDER BY R.key_name ASC;");
    return $this->view->render($response, 'stats/listing.tpl',[
      'stats' => $stats
    ]);
  }

  public function collate($request, $response, $args){
    $version = 1;
    if(isset($args['version'])){
      $version = filter_var($args['version'], FILTER_VALIDATE_INT);
    }
    if(isset($args['stat'])){
      $stat = filter_var($args['stat'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      $p = $request->getQueryParams();
      $start = null;
      $end = null;
      if(isset($p['start']) && isset($p['end'])){
        $start = filter_var($p['start'], FILTER_SANITIZE_STRING, 
         FILTER_FLAG_STRIP_HIGH);
        $end = filter_var($p['end'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
      }
    }
    $minmax = $this->DB->row("SELECT 
      min(STR_TO_DATE(R.datetime, '%Y-%m-%d')) AS min,
      max(STR_TO_DATE(R.datetime, '%Y-%m-%d')) AS max
      FROM tbl_feedback AS R
      WHERE R.key_name = ? AND R.version = ?;", $stat, $version);
    if(!$start) {
      $start = $minmax->min;
      $end = $minmax->max;
    } else {
      $startDate = new \dateTime($start);
      $start = $startDate->format('Y-m-d');
      $endDate = new \dateTime($end);
      $end = $endDate->format('Y-m-d');
    }
    $stat = $this->DB->run("SELECT R.key_name, R.key_type, R.json, R.round_id, R.version, R.datetime FROM tbl_feedback R WHERE R.key_name = ? AND R.version = ? AND R.datetime BETWEEN ? AND ? ORDER BY R.datetime ASC", $stat, $version, $start, $end);
    $stat = $this->statModel->parseStat($stat, TRUE);
    return $this->view->render($response, 'stats/collated.tpl',[
      'stat'  => $stat,
      'start' => $start,
      'end'   => $end,
      'min'   => $minmax->min,
      'max'   => $minmax->max
    ]);
  }
}