<?php

namespace Statbus\Controllers;

use Psr\Container\ContainerInterface;

class Controller {

  protected $container;
  protected $view;
  protected $DB;
  protected $router;
  protected $settings;

  public $page = 1;
  public $pages = 0;
  public $per_page = 60;

  public $breadcrumbs = [];

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->view = $this->container->get('view');
    $this->DB = $this->container->get('DB');
    $this->router = $this->container->get('router');
    }
}

