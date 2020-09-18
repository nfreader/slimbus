<?php

namespace Statbus\Controllers;

use ParagonIE\EasyDB\Exception\ConstructorFailed as CFException;

class DBController {
  
  private $database;
  private $username;
  private $password;
  private $port;
  private $host;
  private $prefix;

  public $db = null;

  public function __construct(array $conn){

    $this->database = $conn['database'];
    $this->username = $conn['username'];
    $this->password = $conn['password'];
    $this->port     = $conn['port'];
    $this->host     = $conn['host'];
    $this->prefix   = $conn['prefix'];

    $options = [
      \PDO::ATTR_PERSISTENT               => TRUE,
      \PDO::ATTR_ERRMODE                  => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE       => \PDO::FETCH_OBJ,
      \PDO::ATTR_STRINGIFY_FETCHES        => FALSE,
      \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => TRUE,
      \PDO::MYSQL_ATTR_COMPRESS           => TRUE,
      \PDO::ATTR_TIMEOUT                  => 5,
      'TABLE_PREFIX'                      => $this->prefix
    ];
    try{
      $this->db = \ParagonIE\EasyDB\Factory::create(
          "mysql:host=$this->host;port=$this->port;dbname=$this->database",
          $this->username,
          $this->password,
          $options
      );
    } catch (CFException $e){
      if(isset($conn['canFail']) && $conn['canFail']){
        $this->db = false;
      }
      else {
        return $e->getMessage();
      }
    }
    
  }
}