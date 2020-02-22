<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Ticket as Ticket;
use Statbus\Controllers\UserController as User;

class TicketController extends Controller {
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $settings = $this->container->get('settings')['statbus'];
    $this->tm = new Ticket($settings);
    // $this->pages = ceil($this->DB->cell("SELECT count(tbl_messages.id) FROM tbl_messages WHERE tbl_messages.deleted = 0
    //   AND (tbl_messages.expire_timestamp > NOW() OR tbl_messages.expire_timestamp IS NULL)") / $this->per_page);
    $this->url = $this->router->pathFor('ticket.index');
  }

  public function getActiveTickets(){
    $tickets = $this->DB->run("
      SELECT 
        t.server_ip,
        t.server_port as port,
        t.round_id as round,
        t.ticket,
        t.action,
        t.message,
        t.timestamp,
        t.recipient as recipient_ckey,
        t.sender as sender_ckey,
        (SELECT `action` FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id ORDER BY id DESC LIMIT 1) as `status`,
        (SELECT COUNT(id) FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id) as `replies`
      FROM tbl_ticket t
      WHERE t.action = 'Ticket Opened' 
      GROUP BY t.id
      ORDER BY `timestamp` DESC
      LIMIT ?, ?;", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($tickets as &$t){
      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function getSingleTicket(int $round, int $ticket, $verifyPlayer = false){
    $round = filter_var($round, FILTER_VALIDATE_INT);
    $ticket = filter_var($ticket, FILTER_VALIDATE_INT);
    $verify = null;
    $verifyPlayer = filter_var('verifyPlayer', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    if ($verifyPlayer) {
      $verify = "AND (t.recipient = ? OR t.sender = ?)";
    }
    $tickets = $this->DB->run("
      SELECT
        t.id,
        t.server_ip,
        t.server_port as port,
        t.round_id as round,
        t.ticket,
        t.action,
        t.message,
        t.timestamp,
        t.recipient as recipient_ckey,
        t.sender as sender_ckey,
        r.rank as r_rank,
        s.rank as s_rank
      FROM tbl_ticket t
      LEFT JOIN tbl_admin AS r ON r.ckey = t.recipient
      LEFT JOIN tbl_admin AS s ON s.ckey = t.sender
      WHERE t.round_id = ?
      AND t.ticket = ? 
      $verify
      ORDER BY `timestamp` ASC;", $round, $ticket, $verifyPlayer, $verifyPlayer);
    foreach ($tickets as &$t){
      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function getPlayerTickets($ckey) {
    $ckey = filter_var($ckey, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $tickets = $this->DB->run("SELECT 
      t.server_ip,
      t.server_port as port,
      t.round_id as round,
      t.ticket,
      t.action,
      t.message,
      t.timestamp,
      t.recipient as recipient_ckey,
      t.sender as sender_ckey,
      r.rank as r_rank,
      s.rank as s_rank,
      COUNT(B.id) AS replies,
      (SELECT `action` FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id ORDER BY id DESC LIMIT 1) as `status`
    FROM tbl_ticket t
    LEFT JOIN tbl_admin AS r ON r.ckey = t.recipient
    LEFT JOIN tbl_admin AS s ON s.ckey = t.sender
    JOIN tbl_ticket AS B ON B.ticket = t.ticket AND B.round_id = t.round_id
    WHERE (t.recipient = ? OR t.sender = ?) AND t.action = 'Ticket Opened'
    GROUP BY t.id
    ORDER BY `timestamp` DESC
    LIMIT ?, ?;", $ckey, $ckey, ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($tickets as &$t){
      $t = $this->tm->parseTicket($t);
    }

    return $tickets;

  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->pages = ceil($this->DB->cell("SELECT
      count(tbl_ticket.id) 
      FROM tbl_ticket 
      WHERE tbl_ticket.action = 'Ticket Opened';") / $this->per_page);
    return $this->view->render($this->response, 'tickets/index.tpl',[
        'tickets' => $this->getActiveTickets(),
        'ticket' => $this,
      ]);
  }


  public function myTickets($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $this->pages = ceil($this->DB->cell("SELECT
      count(t.id) 
      FROM tbl_ticket t
      WHERE (t.recipient = ? OR t.sender = ?) AND t.action = 'Ticket Opened';", $ckey, $ckey) / $this->per_page);
    $this->isPublic = TRUE;
    return $this->view->render($this->response, 'me/tickets.tpl',[
        'tickets' => $this->getPlayerTickets($this->container->user->ckey),
        'ticket' => $this,
      ]);
  }

  public function singlePlayerTicket($request, $response, $args){
    $user = (new User($this->container))->fetchUser();
    return $this->view->render($this->response, 'me/single_ticket.tpl',[
        'tickets' => $this->getSingleTicket($args['round'],$args['ticket'],$user->ckey),
      ]);
  }

  public function single($request, $response, $args){
    return $this->view->render($this->response, 'tickets/single.tpl',[
        'tickets' => $this->getSingleTicket($args['round'],$args['ticket']),
      ]);
  }
}
