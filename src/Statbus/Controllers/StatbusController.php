<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;

class StatbusController {
  // protected $container;
  // protected $view;
  // protected $DB;

  public function __construct($settings) {
    var_dump($settings);
    // $this->container = $container;
    // $this->view = $this->container->get('view');
    // $this->DB = $this->container->get('DB');
    // $this->pages = $this->DB->cell("SELECT count(*) FROM tbl_round");
  }

}