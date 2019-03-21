<?php

namespace Statbus\Models;

class Round {

  private $settings;

  public $id;
  public $initalize_datetime;
  public $start_datetime;
  public $shutdown_datetime;
  public $end_datetime;
  public $ip;
  public $port;
  public $commit;
  public $mode;
  public $result;
  public $state;
  public $shuttle_name;
  public $map;
  public $station_name;
  public $duration;
  public $round_duration;
  public $init_time;
  public $shutdown_time;
  public $deaths;

  public function __construct(array $settings){
    $this->settings = $settings;
  }

  public function parseRound(&$round){

    $round->icons = new \stdclass;
    $round->icons->mode = 'dice-d6';
    $round->icons->result = 'question-circle';

    $round->mode = ucwords($round->mode);
    $round->result = ucwords($round->result);
    $round->end_state = ucwords($round->end_state);

    $round = $this->mapStatus($round);
    $round->server_data = (object) $this->settings['servers'][array_search($round->port, array_column($this->settings['servers'], 'port'))];
   
    if (!$round->server) {
      $round->server = 'Unknown';
    } else {
      $round->server = $round->server_data->name;
    }
    $round->shuttle = preg_replace("/[^a-zA-Z\d\s:]/", '', $round->shuttle);
    $round->shuttle = ucwords($round->shuttle);
    if('' == $round->shuttle) $round->shuttle = false;

    if($round->commit_hash && isset($this->settings['github'])){
      $round->commit_href = "https://github.com/".$this->settings['github']."/commit/$round->commit_hash";
    }

    $round->commit_hash = substr($round->commit_hash, 0,7);

    //Remote Log Links
    $round->logs = FALSE;
    $logs = isset($this->settings['remote_log_src']) ? $this->settings['remote_log_src'] : FALSE;
    if($logs){
      $server = strtolower($round->server->name);
      $date = new \DateTime($round->start_datetime);
      $year = $date->format('Y');
      $month = $date->format('m');
      $day = $date->format('d');
      $round->remote_logs = $round->server_data->public_logs;
      $round->remote_logs.= "$year/$month/$day/round-$round->id.zip";
      $round->remote_logs_dir = str_replace('.zip', '', $round->remote_logs);
      $round->admin_logs_dir = str_replace($round->server_data->public_logs, $round->server_data->raw_logs, $round->remote_logs_dir);
      $round->logs = TRUE;
    }

    $round->map_url = str_replace(' ', '', $round->map);
    return $round;
  }

  public function mapStatus(&$round) {
    @$round->icons->mode = $this->settings['mode_icons'][$round->mode];
    if ('' === $round->result || 'Undefined' === $round->result){
      $round->result = $round->end_state;
    }
    if(strpos($round->result, 'Win - ') !== FALSE){
      $round->class = 'success';
      $round->icons->result = 'check';
    } else if (strpos($round->result, 'Loss - ') !== FALSE) {
      $round->class = 'danger';
      $round->icons->result = 'times';
    } else if (strpos($round->result, 'Halfwin - ') !== FALSE) {
      $round->class = 'warning';
      $round->icons->result = 'minus';
    } else if (strpos($round->result, 'Admin Reboot - ') !== FALSE) {
      $round->class = 'reboot';
      $round->icons->result = 'redo';
    } else if ('Nuke' === $round->result) {
      $round->class = 'inverse';
      $round->icons->result = 'bomb';
    } else if ('Restart Vote' === $round->result) {
      $round->class = 'vote';
      $round->icons->result = 'user-check';
    } else {
      $round->class = 'proper';
      $round->icons->result = 'check';
    }
    return $round;
  }

}