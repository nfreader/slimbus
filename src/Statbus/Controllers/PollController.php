<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Poll as Poll;

class PollController Extends Controller {
  
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->pages = ceil($this->DB->cell("SELECT count(tbl_poll_question.id) FROM tbl_poll_question WHERE tbl_poll_question.adminonly != 1
      AND tbl_poll_question.dontshow IS NULL") / $this->per_page);

    $this->pollModel = new Poll();
    $this->breadcrumbs['Polls'] = $this->router->pathFor('poll.index');

  }

  public function index($request, $response, $args){
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $polls = $this->DB->run("SELECT P.*,
    SEC_TO_TIME(TIMESTAMPDIFF(SECOND, P.starttime, P.endtime)) AS duration,
    IF(P.endtime < NOW(), 1, 0) AS ended,
    count(tbl_poll_vote.id) + count(tbl_poll_textreply.id)  as totalVotes
    FROM tbl_poll_question P
    LEFT JOIN tbl_poll_vote ON P.id = tbl_poll_vote.pollid
    LEFT JOIN tbl_poll_textreply ON P.id = tbl_poll_textreply.pollid
    WHERE (P.dontshow = 0 OR  P.dontshow = 1 AND P.endtime < NOW())
    AND P.adminonly = 0
    GROUP BY P.id
    ORDER BY P.id DESC
    LIMIT ?,?", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach($polls as &$p){
      $p = $this->pollModel->parsePoll($p);
    }
    return $this->view->render($response, 'polls/listing.tpl',[
      'polls'       => $polls,
      'poll'        => $this,
      'breadcrumbs' => $this->breadcrumbs
    ]);
  }

  public function single($request, $response, $args) {
    $poll = $this->getPoll($args['id']);
    $url = parent::getFullURL($this->router->pathFor('poll.single',['id'=>"#$poll->id"]));
    $this->breadcrumbs[$poll->id] = $url;
    return $this->view->render($response, 'polls/single.tpl',[
      'poll'        => $poll,
      'breadcrumbs' => $this->breadcrumbs,
      'ogdata'      => $this->ogdata
    ]);
  }


  public function getPoll($id){
    $id = filter_var($id, FILTER_VALIDATE_INT);
    $poll = $this->DB->row("SELECT tbl_poll_question.*,
    SEC_TO_TIME(TIMESTAMPDIFF(SECOND, tbl_poll_question.starttime, tbl_poll_question.endtime)) AS duration,
    IF(tbl_poll_question.endtime < NOW(), 1, 0) AS ended,
    count(tbl_poll_vote.id) + count(tbl_poll_textreply.id) as totalVotes
    FROM tbl_poll_question
    LEFT JOIN tbl_poll_vote ON tbl_poll_question.id = tbl_poll_vote.pollid
    LEFT JOIN tbl_poll_textreply ON tbl_poll_question.id = tbl_poll_textreply.pollid
    WHERE tbl_poll_question.id = ?
    AND (tbl_poll_question.dontshow = 0 OR  tbl_poll_question.dontshow = 1 AND tbl_poll_question.endtime < NOW())
    AND tbl_poll_question.adminonly = 0
    GROUP BY tbl_poll_question.id
    ORDER BY tbl_poll_question.id DESC", $id);

    if('TEXT' == $poll->polltype){
      $poll->results = $this->DB->run("SELECT * FROM tbl_poll_textreply WHERE pollid = ?", $poll->id);
    } else {
      if (!$filter){
        $poll->results = $this->DB->run("SELECT COUNT(tbl_poll_vote.id) AS votes,
        tbl_poll_option.text AS `option`
        FROM tbl_poll_vote
        LEFT JOIN tbl_poll_option ON tbl_poll_vote.optionid = tbl_poll_option.id
        WHERE tbl_poll_vote.pollid = ?
        GROUP BY tbl_poll_vote.optionid
        ORDER BY votes DESC", $poll->id);
      } else {
        $poll->results = $this->DB->run("SELECT
        COUNT(o.id) AS votes,
        o.text AS `option`
        FROM tbl_poll_vote AS v
        LEFT JOIN tbl_poll_option AS o ON (v.optionid = o.id)
        LEFT JOIN tbl_player AS p ON (v.ckey = p.ckey)
        LEFT JOIN tbl_poll_question AS q ON (v.pollid = q.id) 
        WHERE v.pollid = ?
        AND
          (SELECT SUM(j.delta)
          FROM tbl_role_time_log AS j
          WHERE j.job IN ('Living')
          AND j.datetime BETWEEN q.starttime - INTERVAL 30 DAY AND q.starttime
          AND j.ckey = v.ckey) >= 60
          GROUP BY o.text
        ORDER BY votes DESC;", $poll->id);
      }
    }

    $poll = $this->pollModel->parsePoll($poll);
    return $poll;
  }



}