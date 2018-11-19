<?php

namespace Statbus\Models;

class Library {

  private $settings;
  public $deaths;

  public function __construct(){
    $this->md = new \Parsedown();
  }

  public function parseBook(&$book){
    switch($book->category){

      default:
      case 'Ficton':
        $book->class = 'success';
      break;

      case 'Non-Fiction':
        $book->class = 'info';
      break;

      case 'Reference':
        $book->class = 'warning';
      break;

      case 'Religion':
        $book->class = 'primary';
      break;

      case 'Adult':
        $book->class = 'danger censored';
      break;

    }
    if(isset($book->content)){
      $book->content = $this->md->text($book->content);
    }
    return $book;
  }

}

