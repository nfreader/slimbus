<?php

namespace Statbus\Models;

class Messages {

  private $settings;

  public function __construct(array $settings){
    $this->settings = $settings;
    $this->md = new \Parsedown();
  }

  public function parseMessage(&$message) {
    $message->text = strip_tags($message->text);
    $message->text = $this->md->text($message->text);
    $message->text = str_replace([
      '\"',
      "\'",
      "\n"
    ], [
      '"',
      "'",
      "<br>"
    ], $message->text);
    switch ($message->type){
      case 'memo':
        $message->icon  = 'sticky-note';
        $message->class = 'bg-primary text-white';
      break;

      case 'message':
        $message->icon = 'envelope';
        $message->class = 'bg-success text-white';
      break;

      case 'message sent':
        $message->icon = 'envelope-open';
        $message->class = 'border-success';
      break;

      default:
      case 'note':
        $message->icon = 'flag';
        $message->class = 'border-warning';
      break;

      case 'watchlist entry':
        $message->icon = 'binoculars';
        $message->class = 'bg-danger text-white';
      break;

    }

    if(isset($message->severity)){
      switch ($message->severity) {
        case 'high':
          $message->severity_class = 'danger';
        break;

        case 'medium':
          $message->severity_class = 'warning';
        break;

        case 'minor':
          $message->severity_class = 'info';
        break;

        default:
        case 'none':
          $message->severity_class = 'success';
          $message->severity = "None";
        break;
      }
      $message->severity = ucwords($message->severity);
    }

    $message->type = ucwords($message->type);

    return $message;
  }
}