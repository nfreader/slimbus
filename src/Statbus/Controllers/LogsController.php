<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Controllers\DBController as DBController;
use GuzzleHttp\Exception\ClientException as GCeption;

class LogsController Extends Controller {

  private $hash;
  private $round;
  private $hasLogs = false;
  private $file;

  public $listing;

  public function __construct(ContainerInterface $container, $round) {
    parent::__construct($container);
    $settings = $this->container->get('settings');
    $this->round = $round;
    $this->hash = hash('sha256', $this->round->remote_logs);
    $this->zip = ROOTDIR."/tmp/logs/$this->hash.zip";
    $this->hasLogs = $this->ensureLocalLogs();
    $this->alt_db = (new DBController($settings['database']['alt']))->db;
  }

  private function ensureLocalLogs(){
    if(file_exists($this->zip)){
      return true;
    }
    try{
    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET',$this->round->remote_logs,[
      'headers' => ['Accept-Encoding' => 'gzip'],
      'curl' => [
          CURLOPT_FOLLOWLOCATION => TRUE,
          CURLOPT_REFERER => "atlantaned.space",
        ]
      ]);
    } catch (GCeption $e){
      return false;
    } 
    $logs = $res->getBody()->getContents();
    if(!$logs){
      return false;
    }
    try{
      $handle = fopen($this->zip, 'w+');
      fwrite($handle, $logs);
      fclose($handle);
    } catch(Excention $e){
      die($e->getMessage());
    }
    return true;
  }

  public function listing(){
    $files = new \RecursiveDirectoryIterator("phar://".$this->zip);
    foreach ($files as $name => $file){
      if (!$file->isFile()) continue;
      $name = str_replace("phar://$this->zip/$file/", '', $name);
      $this->listing[$name] = $file;
    }
    return $this->listing;
  }
  public function getFile($file, $raw = false){
    if (!in_array($file, [
      'atmos.html',
      // 'attack.txt',
      'cargo.html',
      // 'game.txt',
      'gravity.html',
      'hallucinations.html',
      'initialize.txt',
      'manifest.txt',
      'newscaster.json',
      'pda.txt',
      'portals.html',
      'qdel.txt',
      'radiation.html',
      'records.html',
      'research.html',
      'round_end_data.json',
      'runtime.txt',
      'singulo.html',
      'supermatter.html',
      'wires.html',
    ])) {
      return false;
    }
    if(file_exists("phar://".$this->zip.'/'.$file)){
      $this->file = file_get_contents("phar://".$this->zip."/".$file);
    }
    if(in_array($file,[
      'newscaster.json'
    ])){
      $this->file = strip_tags($this->file,'<br>');
    } else {
    $this->file = filter_var($this->file, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
    }
    if ($raw){
      return $this->file;
    }
    $this->parseLogFile($file);
    return $this->file;
  }

  private function parseLogFile($file){
    switch($file){
      default:

      break;

      case 'atmos.html':
        $this->genericLogParse('atmos.html');
        $this->parseAtmosLogs();
      break;

      case 'gravity.html':
        $this->genericLogParse('gravity.html');
      break;

      case 'hallucinations.html':
        $this->genericLogParse('hallucinations.html');
      break;

      case 'manifest.txt':
        $this->parseManifest();
      break;

      case 'newscaster.json':
        $this->parseNewscaster();
      break;

      case 'pda.txt':
        $this->parsePDA();
      break;

      case 'records.html':
        $this->genericLogParse('records.html');
        $this->parseRecords();
      break;

      case 'research.html':
        $this->genericLogParse('research.html');
      break;

      case 'singulo.html':
        $this->genericLogParse('singulo.html');
      break;

      case 'supermatter.html':
        $this->genericLogParse('supermatter.html');
      break;

      case 'wires.html':
        $this->genericLogParse('wires.html');
      break;
    }
  }

  private function parseAtmosLogs(){

  }

  private function parseRecords(){
    foreach ($this->file as &$l){
      $l['text'] = str_replace(' None ', " <div class='badge badge-light btn-sm'>None</div> ", $l['text']);
      $l['text'] = str_replace('*Arrest*', " <div class='badge badge-danger btn-sm'>Arrest</div> ", $l['text']);
      $l['text'] = str_replace('Incarcerated', " <div class='badge badge-warning btn-sm'>Incarcerated</div> ", $l['text']);
      $l['text'] = str_replace('Discharged', " <div class='badge badge-primary btn-sm'>Discharged</div> ", $l['text']);
    }
    return $file;
  }
  private function genericLogParse($file){
    $lines = [];
    $matches = [];
    preg_match_all("/(\d\d:\d\d:\d\d) \[(\S*)\] \((\d{1,3}),(\d{1,3}),(\d{1,3})\) \|\| (.*)$/mi", $this->file, $matches, PREG_SET_ORDER);
    foreach ($matches as $tmp){
      $entry = [];
      $entry['timestamp'] = $tmp[1];
      $entry['device'] = $tmp[2];
      $entry['x'] = $tmp[3];
      $entry['y'] = $tmp[4];
      $entry['z'] = $tmp[5];
      $entry['text'] = $tmp[6];
      $entry['color'] = substr(sha1($entry['device']), 0, 6);
      $lines[] = $entry;
    }
    $this->file = $lines;
  }
  private function parseManifest(){
    $lines = [];
    $matches = [];
    preg_match_all("/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.\d{3})\] ([a-zA-Z0-9]+) \\\\ (.*) \\\\ (.*) \\\\ (.*) \\\\ (ROUNDSTART|LATEJOIN)$/mi", $this->file, $matches, PREG_SET_ORDER);
    foreach ($matches as $tmp){
      $entry = [];
      $entry['timestamp'] = $tmp[1];
      $entry['ckey'] = $tmp[2];
      $entry['character'] = $tmp[3];
      $entry['job'] = $tmp[4];
      $entry['special'] = ucwords($tmp[5]);
      if('NONE' === $entry['special']) $entry['special'] = false;
      $entry['when'] = $tmp[6];
      ('ROUNDSTART' === $entry['when']) ? $entry['when'] = TRUE : $entry['when'] = FALSE;
      $lines[] = $entry;
    }
    $this->file = $lines;
    $this->saveManifest();
  }

  private function parseNewscaster(){
    $file = json_decode($this->file, TRUE);
    foreach ($file as &$c){
      foreach ($c['messages'] as $k => &$v){
        if (!is_array($v)){
          $tmp = $c['messages'];
          $tmp['id'] = substr(sha1($tmp['body'].$tmp['time stamp']),0,7);
          $tmp['body'] = filter_var($tmp['body'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
          unset($c['messages']);
          $c['messages'][] = $tmp;
          continue 2; 
        } else {
          $v['id'] = substr(sha1($v['body'].$v['time stamp']),0,7);
          $v['body'] = filter_var($v['body'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_NO_ENCODE_QUOTES);
          if('' != $v['photo file']){
            $v['photo file'] = base64_encode(file_get_contents("phar://".$this->zip."/photos/".$v['photo file'].".png"));
          }
         }
      }
    }
    $this->file = $file;
  }

  private function saveManifest(){
    if(!$this->alt_db) return false;
    if($this->round->id === $this->alt_db->cell("SELECT round_id FROM manifest WHERE round_id = ?", $this->round->id)) return false;
    foreach($this->file as $e){
      try{
        $this->alt_db->insert('manifest',[
          'round_id'   => $this->round->id,
          'name'       => $e['character'],
          'ckey'       => $e['ckey'],
          'job'        => $e['job'],
          'role'       => $e['special'],
          'roundstart' => (int) $e['when']
        ]);
      } catch (Exception $e){
        var_dump($e->getMessage());
      }
    }
  }

  private function parsePDA(){
    $lines = [];
    $matches = [];
    //EDGECASE: Sometimes the PDA name isn't specified
    $this->file = str_replace(") (PDA: PDA to ", ") (PDA: Unknown Device PDA to ", $this->file);
    preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.\d{3})\] (PDA|COMMENT): (.+?)\/\((.+?)\) \(PDA: (.+?)PDA to (.+?) \((.+?)\)\) "(.+?)" \((.+?) \((\d{1,3}), (\d{1,3}), (\d{1,3})/mi', $this->file, $matches, PREG_SET_ORDER);
    foreach ($matches as $tmp){
      $entry = [];
      $entry['timestamp']      = $tmp[1];
      $entry['type']           = $tmp[2];
      $entry['from_ckey']      = $tmp[3];
      $entry['from_character'] = $tmp[4];
      $entry['from_device']    = ucwords($tmp[5]);
      $entry['to_character']   = $tmp[6];
      $entry['to_job']         = $tmp[7];
      $entry['message']        = $tmp[8];
      $entry['from_area']      = $tmp[9];
      $entry['from_x']         = $tmp[10];
      $entry['from_y']         = $tmp[11];
      $entry['from_z']         = $tmp[12];
      $entry['id'] = substr(hash('sha512', $entry['timestamp'].$entry['message']), 0,6);
      $lines[] = $entry;
    }
    $this->file = $lines;
  }

  public function getPages(){
    if(!$this->alt_db) return false;
    $pages = $this->alt_db->cell("SELECT count(*) FROM round_logs WHERE round = ?", $this->round->id);
    $this->pages = ceil($pages / 1000);
    return ceil($pages / 1000);
  }

  public function getGameLogs(){
    if(!$this->alt_db) return false;
    if(!$this->checkForLogs()) $this->processGameLogs();
    return $this->alt_db->run("SELECT `timestamp`, `type`, `text`, x, y, z, area, id
      FROM round_logs
      WHERE round = ?
      ORDER BY `timestamp` ASC
      LIMIT ?,?", $this->round->id, ($this->round->page*1000) - 1000, 1000);
  }

  public function checkForLogs(){
    return $this->alt_db->cell("SELECT round FROM round_logs WHERE round = ?",$this->round->id);
  }

  public function processGameLogs(){
    $i = 0;
    $handle = fopen("phar://".$this->zip."/game.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
          $tmp = [];
          $entry = [];
          if(!preg_match("/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.\d{3})] (\w*): (.*)/", $line, $tmp)) continue;
          $entry['type'] = $tmp[2];
          $entry['time'] = $tmp[1];
          $entry['text'] = $tmp[3];
          $entry['x']    = null;
          $entry['y']    = null;
          $entry['z']    = null;
          $entry['area'] = null;
          if(strpos($entry['text'], ') (DEAD) "') !== FALSE) {
            $entry['type'] = "DSAY";
          }
          if(strpos($entry['text'], ') (ghost) "') !== FALSE) {
            continue;
          }
          unset($tmp);
          if(isset($entry['text'])){
            $t = [];
            if(preg_match("/(.*) \((.*)\((\d{1,}), (\d{1,}), (\d{1,})\)\)/", $entry['text'], $t)){
              $entry['text'] = $t[1];
              $entry['x']    = $t[3];
              $entry['y']    = $t[4];
              $entry['z']    = $t[5];
              $entry['area'] = $t[2];
              // var_dump($merge);
            }
            try{
              $this->alt_db->insert('round_logs',[
              'round'     => $this->round->id,
              'timestamp' => $entry['time'],
              'type'      => $entry['type'],
              'text'      => filter_var($entry['text'], FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_NO_ENCODE_QUOTES),
              'x'         => $entry['x'],
              'y'         => $entry['y'],
              'z'         => $entry['z'],
              'area'      => filter_var($entry['area'],FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_NO_ENCODE_QUOTES)
            ]);
              $i++;
            } catch (Exception $e){
              echo $e->getMessage();
            }
          }
        }
      fclose($handle);
    }

    $handle = fopen("phar://".$this->zip."/attack.txt", "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
          $tmp = [];
          $entry = [];
          if(!preg_match("/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}.\d{3})] (\w*): (.*)/", $line, $tmp)) continue;
          $entry['type'] = $tmp[2];
          $entry['time'] = $tmp[1];
          $entry['text'] = $tmp[3];
          $entry['x']    = null;
          $entry['y']    = null;
          $entry['z']    = null;
          $entry['area'] = null;
          unset($tmp);
          if(isset($entry['text'])){
            $t = [];
            if(preg_match("/(.*) \((.*)\((\d{1,}), (\d{1,}), (\d{1,})\)\)/", $entry['text'], $t)){
              $entry['text'] = $t[1];
              $entry['x']    = $t[3];
              $entry['y']    = $t[4];
              $entry['z']    = $t[5];
              $entry['area'] = $t[2];
              // var_dump($merge);
            }
            try{
              $this->alt_db->insert('round_logs',[
              'round'     => $this->round->id,
              'timestamp' => $entry['time'],
              'type'      => $entry['type'],
              'text'      => filter_var($entry['text'], FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_NO_ENCODE_QUOTES),
              'x'         => $entry['x'],
              'y'         => $entry['y'],
              'z'         => $entry['z'],
              'area'      => filter_var($entry['area'], FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_FLAG_NO_ENCODE_QUOTES)
            ]);
              $i++;
            } catch (Exception $e){
              echo $e->getMessage();
            }
          }
        }
      fclose($handle);
    }
  }
}