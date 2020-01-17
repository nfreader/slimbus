<?php

namespace Statbus\Models;

class Ticket {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
    $this->lastDate = null;
  }

  public function parseTicket(&$ticket){
    if($this->lastDate){
      $interval = date('U',strtotime($ticket->timestamp)) - date('U',strtotime($this->lastDate));
      $ticket->interval = date('i:s', $interval);
    }
    $ticket->class = 'danger';
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
    if('Reply' === $ticket->action){
      $ticket->class = "success";
    }

    $this->lastDate = $ticket->timestamp;
    
    return $ticket;
  }
}
