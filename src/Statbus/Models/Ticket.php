<?php

namespace Statbus\Models;

use Statbus\Models\Player as Player;

class Ticket {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
    $this->lastDate = null;
    $this->pm = new Player($settings);
  }

  public function parseTicket(&$ticket){

    $ticket->sender = new \stdclass;
    $ticket->sender->ckey = $ticket->sender_ckey;
    $ticket->sender->rank = $ticket->s_rank;
    $ticket->sender = $this->pm->parsePlayer($ticket->sender);

    $ticket->recipient = new \stdclass;
    $ticket->recipient->ckey = $ticket->recipient_ckey;
    $ticket->recipient->rank = $ticket->r_rank;
    $ticket->recipient = $this->pm->parsePlayer($ticket->recipient);
    
    if(($ticket->sender->ckey && $ticket->recipient->ckey) && 'Ticket Opened' === $ticket->action){
      $ticket->bwoink = TRUE;
    }

    if($this->lastDate){
      $interval = date('U',strtotime($ticket->timestamp)) - date('U',strtotime($this->lastDate));
      $ticket->interval = date('i:s', $interval);
    }
    $ticket->class = 'danger';
    $ticket->status_class = 'success';
    $ticket->type = 'text';
    $ticket->message = strip_tags($ticket->message);
    $ticket->server_data = (object) $this->settings['servers'][array_search($ticket->port, array_column($this->settings['servers'], 'port'))];
    switch ($ticket->action) {
      case 'Reply':
      default:
        $ticket->class = "success";
        $ticket->action_label = "Reply from ";
      break;

      case 'Ticket Opened':
        if (null === $ticket->recipient->ckey){
          $ticket->recipient = FALSE;
          $ticket->class = "primary";
        }
        $ticket->action_label = "Ticket Opened by";
      break;

      case 'Resolved':
        $ticket->class = "success";
        $ticket->action_label = "Resolved by ";
        $ticket->recipient = FALSE;
        $ticket->message = FALSE;
        $ticket->icon = "thumbs-up";
        $ticket->type = "action";
      break;

      case 'Closed':
        $ticket->class = "danger";
        $ticket->action_label = "Closed by ";
        $ticket->recipient = FALSE;
        $ticket->message = FALSE;
        $ticket->type = "action";
        $ticket->icon = "times-circle";
      break;

      case 'Rejected':
        $ticket->class = "danger";
        $ticket->action_label = "Rejected by ";
        $ticket->recipient = FALSE;
        $ticket->message = FALSE;
        $ticket->type = "action";
        $ticket->icon = "undo";
      break;

      case 'IC Issue':
        $ticket->class = "dark";
        $ticket->action_label = "Marked as IC issue by ";
        $ticket->recipient = FALSE;
        $ticket->message = FALSE;
        $ticket->type = "action";
        $ticket->icon = "gavel";
      break;

      case 'Disconnected':
        $ticket->class = "dark";
        $ticket->action_label = "Client disconnected";
        $ticket->sender = $ticket->recipient;
        $ticket->recipient = false;
        $ticket->message = FALSE;
        $ticket->type = "action";
        $ticket->icon = "window-close";
      break;

      case 'Reconnected':
        $ticket->class = "success";
        $ticket->action_label = "Client reconnected";
        $ticket->sender = $ticket->recipient;
        $ticket->recipient = false;
        $ticket->message = FALSE;
        $ticket->type = "action";
        $ticket->icon = "network-wired";
      break;
    }

    switch ($ticket->status){
      case 'Reply':
      case 'Ticket Opened':
      case 'Reconnected':
        $ticket->status_class = 'warning';
        // $ticket->status = "Open";
      break;

      case 'Disconnected':
        $ticket->status_class = 'secondary';
        // $ticket->status = "Open";
      break;

      default:
        $ticket->status_class = 'success';
        // $ticket->status = "Resolved";
    }

    $this->lastDate = $ticket->timestamp;
    
    return $ticket;
  }
}
