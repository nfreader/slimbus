<?php

namespace Statbus\Models;

class Poll {

  private $settings;

  public function __construct(){

  }

  public function parsePoll(&$poll){
    switch($poll->polltype){
      case 'OPTION':
        $poll->type = "Option";
      break;
      case 'TEXT':
        $poll->type = "Text Reply";
      break;
      case 'NUMVAL':
        $poll->type = "Numerical Rating";
      break;
      case 'MULTICHOICE':
        $poll->type = "Multiple Choice";
      break;
      case 'IRV':
        $poll->type = "Instant Runoff Voting";
      break;
    }
    if(isset($poll->results)){
      foreach($poll->results as &$r){
        $r->percent = floor(($r->votes / $poll->totalVotes) * 100);
      }
    }
    return $poll;
  }


  public function mapPollType($type){

  }

}

