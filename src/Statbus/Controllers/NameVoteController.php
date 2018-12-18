<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
// use Statbus\Controllers\User as User;


class NameVoteController Extends Controller {
  
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $settings = $this->container->get('settings');
    $this->alt_db = (new DBController($settings['database']['alt']))->db;
    $this->user = $this->container->get('user')->user;
  }

  public function index($request, $response, $args){
    return $this->view->render($response, 'misc/namevote/vote.tpl',[
      'name'      => $this->getname()
    ]);
  }

  public function cast($request, $response, $args){
    $args = $request->getParams();
    if(!isset($args)){
      return json_encode(['error'=>'Missing vote arguments']);
    }

    if(!$this->user){
      return json_encode(['error'=>'You must be logged in to vote for names!']); 
    }
    if('nay'===$args['vote']) {
      $args['vote'] = 0;
    } else {
      $args['vote'] = 1;
    }
    if($this->alt_db->row("SELECT name, ckey FROM name_vote WHERE name = ? and ckey = ?",$args['name'], $this->user->ckey)){
      return json_encode(['name'=>$this->getName(),'args'=>$args]);
    }
    try{
      $this->alt_db->insert('name_vote',[
        'name' => $args['name'],
        'good' => $args['vote'],
        'ckey' => $this->user->ckey,
      ]);
    } catch (Exception $e){
      return json_encode(['name'=>$this->getName(), 'args'=>$args]); 
    }
    return json_encode(['name'=>$this->getName(),'args'=>$args]);
  }

  public function rankings($request, $response, $args) {
    $rank = 'best';
    $rank = filter_var($args['rank'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    if('worst' === $rank){
      $ranking = $this->alt_db->run("SELECT `name`,
        IFNULL(count(good),1) - sum(good) AS `no`,
        sum(good) AS `yes`
        FROM name_vote
        GROUP BY `name`
        ORDER BY `no` DESC
        LIMIT 0, 100;");
    } else {
      $ranking = $this->alt_db->run("SELECT `name`,
        IFNULL(count(good),1) - sum(good) AS `no`,
        sum(good) AS `yes`
        FROM name_vote
        GROUP BY `name`
        ORDER BY `yes` DESC
        LIMIT 0, 100;");
    }
    return $this->view->render($response, 'misc/namevote/results.tpl',[
      'ranking' => $ranking
    ]);
  }

  public function getName(){
    $name = $this->DB->row("SELECT DISTINCT `name`, job
    FROM tbl_death
    WHERE YEAR(`tod`) = 2018 AND `job` IN ('Assistant', 'Atmospheric Technician', 'Bartender', 'Botanist', 'Captain', 'Cargo Technician', 'Chaplain', 'Chemist', 'Chief Engineer', 'Chief Medical Officer', 'Cook', 'Curator', 'Detective', 'Geneticist', 'Head of Personnel', 'Head of Security', 'Janitor', 'Lawyer', 'Librarian', 'Medical Doctor', 'Quartermaster', 'Research Director', 'Roboticist', 'Scientist', 'Security Officer', 'Shaft Miner', 'Station Engineer', 'Virologist', 'Warden')
    ORDER BY RAND()
    LIMIT 0,1;");
    return $name;
  }

}