<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;
use Statbus\Models\Ticket as Ticket;
use Statbus\Models\Player as Player;


class TicketController extends Controller {
  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $settings = $this->container->get('settings')['statbus'];
    $this->tm = new Ticket($settings);
    $this->pm = new Player($settings);
    // $this->pages = ceil($this->DB->cell("SELECT count(tbl_messages.id) FROM tbl_messages WHERE tbl_messages.deleted = 0
    //   AND (tbl_messages.expire_timestamp > NOW() OR tbl_messages.expire_timestamp IS NULL)") / $this->per_page);
    $this->url = $this->router->pathFor('ticket.index');
    $this->path = 'ticket.single';
    $this->permaLink = 'ticket.single';
    $settings = $this->container->get('settings');
    $this->alt_db = (new DBController($settings['database']['alt']))->db;
  }

  public function getActiveTickets(){
    $this->pages = ceil($this->DB->cell("SELECT
      count(tbl_ticket.id) 
      FROM tbl_ticket 
      WHERE tbl_ticket.action = 'Ticket Opened';") / $this->per_page);
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
        'player' as r_rank,
        'player' as s_rank,
        (SELECT `action` FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id ORDER BY id DESC LIMIT 1) as `status`,
        (SELECT COUNT(id) FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id) as `replies`
      FROM ss13ticket t
      WHERE t.action = 'Ticket Opened' 
      GROUP BY t.id
      ORDER BY `timestamp` DESC
      LIMIT ?, ?;", ($this->page * $this->per_page) - $this->per_page, $this->per_page);
    foreach ($tickets as &$t){
      $t->sender = new \stdclass;
      $t->sender->ckey = $t->sender_ckey;
      $t->sender->rank = $t->s_rank;
      $t->sender = $this->pm->parsePlayer($t->sender);

      $t->recipient = new \stdclass;
      $t->recipient->ckey = $t->recipient_ckey;
      $t->recipient->rank = $t->r_rank;
      $t->recipient = $this->pm->parsePlayer($t->recipient);

      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function getTicketsForRound(int $round) {
    $round = filter_var($round, FILTER_VALIDATE_INT);
    $tickets = $this->DB->run("SELECT
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
        s.rank as s_rank,
        (SELECT `action` FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id ORDER BY id DESC LIMIT 1) as `status`,
        (SELECT COUNT(id) FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id) as `replies`
      FROM tbl_ticket t
      LEFT JOIN tbl_admin AS r ON r.ckey = t.recipient
      LEFT JOIN tbl_admin AS s ON s.ckey = t.sender
      WHERE t.round_id = ?
      AND t.action = 'Ticket Opened'
      ORDER BY `timestamp` ASC;", $round);
    foreach ($tickets as &$t){
      $t->sender = new \stdclass;
      $t->sender->ckey = $t->sender_ckey;
      $t->sender->rank = $t->s_rank;
      $t->sender = $this->pm->parsePlayer($t->sender);

      $t->recipient = new \stdclass;
      $t->recipient->ckey = $t->recipient_ckey;
      $t->recipient->rank = $t->r_rank;
      $t->recipient = $this->pm->parsePlayer($t->recipient);

      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function getSingleTicket(int $round, int $ticket){
    $round = filter_var($round, FILTER_VALIDATE_INT);
    $ticket = filter_var($ticket, FILTER_VALIDATE_INT);
    $tickets = $this->DB->run("SELECT
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
      ORDER BY `timestamp` ASC;", $round, $ticket);
    foreach ($tickets as &$t){
      $t->sender = new \stdclass;
      $t->sender->ckey = $t->sender_ckey;
      $t->sender->rank = $t->s_rank;
      $t->sender = $this->pm->parsePlayer($t->sender);

      $t->recipient = new \stdclass;
      $t->recipient->ckey = $t->recipient_ckey;
      $t->recipient->rank = $t->r_rank;
      $t->recipient = $this->pm->parsePlayer($t->recipient);

      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function getTicketsForCkey(string $ckey) {
    $this->pages = ceil($this->DB->cell("SELECT
      count(t.id) 
      FROM tbl_ticket t
      WHERE t.action = 'Ticket Opened' AND (t.recipient = ? OR t.sender = ?);", $ckey, $ckey) / $this->per_page);
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
        'player' as r_rank,
        'player' as s_rank,
        (SELECT `action` FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id ORDER BY id DESC LIMIT 1) as `status`,
        (SELECT COUNT(id) FROM tbl_ticket WHERE t.ticket = ticket AND t.round_id = round_id) as `replies`
      FROM ss13ticket t
      WHERE t.action = 'Ticket Opened'
      AND (t.recipient = ? OR t.sender = ?)
      GROUP BY t.id
      ORDER BY `timestamp` DESC
      LIMIT ?, ?;", $ckey, $ckey, ($this->page * $this->per_page) - $this->per_page, $this->page * $this->per_page);
      // var_dump(($this->page * $this->per_page) - $this->per_page);
      // var_dump($this->page * $this->per_page);
    foreach ($tickets as &$t){
      $t->sender = new \stdclass;
      $t->sender->ckey = $t->sender_ckey;
      $t->sender->rank = $t->s_rank;
      $t->sender = $this->pm->parsePlayer($t->sender);

      $t->recipient = new \stdclass;
      $t->recipient->ckey = $t->recipient_ckey;
      $t->recipient->rank = $t->r_rank;
      $t->recipient = $this->pm->parsePlayer($t->recipient);

      $t = $this->tm->parseTicket($t);
    }
    return $tickets;
  }

  public function index($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    return $this->view->render($this->response, 'tickets/index.tpl',[
        'tickets' => $this->getActiveTickets(),
        'ticket' => $this,
      ]);
  }

  public function roundTickets($request, $response, $args){
    $this->path = 'ticket.round';
    return $this->view->render($this->response, 'tickets/round.tpl',[
        'tickets' => $this->getTicketsForRound($args['round']),
        'round' => $args['round'],
        'ticket' => $this
      ]);
  }

  public function single($request, $response, $args){
    return $this->view->render($this->response, 'tickets/single.tpl',[
        'tickets' => $this->getSingleTicket($args['round'],$args['ticket']),
      ]);
  }
  public function myTickets($request, $response, $args) {
    if(isset($args['page'])) {
      $this->page = filter_var($args['page'], FILTER_VALIDATE_INT);
    }
    $user = $this->container->get('user');
    $this->path = "me.tickets";
    $this->permaLink = "me.tickets.single";
    return $this->view->render($this->response, 'tickets/me.tpl',[
      'tickets' => $this->getTicketsForCkey($user->ckey),
      'ticket' => $this,
    ]);
  }
  public function myTicket($request, $response, $args){
    $this->user = $this->container->get('user');
    $tickets = $this->getSingleTicket($args['round'], $args['ticket']);
    if(!in_array($this->user->ckey, [$tickets[0]->sender_ckey, $tickets[0]->recipient_ckey])) {
      return $this->view->render($this->response, 'base/error.tpl',[
        'message' => 'You do not have permission to view this',
        'code' => 403
      ]);
    }
    return $this->view->render($this->response, 'tickets/single.me.tpl',[
      'tickets' => $tickets,
    ]);
  }
}
