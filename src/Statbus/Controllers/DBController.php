<?php

namespace Statbus\Controllers;

class DBController {
  
  private $database;
  private $username;
  private $password;
  private $port;
  private $host;
  public $prefix;

  public  $db = null;

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
      'TABLE_PREFIX'                      => $this->prefix
    ];
    try{
      $db = \ParagonIE\EasyDB\Factory::create(
          "mysql:host=$this->host;port=$this->port;dbname=$this->database",
          $this->username,
          $this->password,
          $options
      );
      $this->db = $db;
    } catch (Exception $e){
      var_dump($e->getMessage());

    }
    
  }
}