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
  public $ogdata = [];

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
    $this->DB = $this->container->get('DB');
    $this->view = $this->container->get('view');
    $this->router = $this->container->get('router');
    $this->request = $this->container->get('request');
    $this->response = $this->container->get('response');
    $this->ogdata = [
      'site_name' => $this->container->get('settings')['statbus']['app_name'],
      'url'       => $this->request->getUri()->getBaseUrl().$this->router->pathFor('statbus'),
      'type'      => 'object',
    ];
    $this->view->getEnvironment()->addGlobal('ogdata', $this->ogdata);
    $this->view->getEnvironment()->addGlobal('settings', $this->container->get('settings')['statbus']);
    if(!$this->DB){
      $error = $this->view->render($this->response, 'base/error_critical.tpl',[
        'message' => "Unable to establish a connection to the statistics database.",
        'text' => 'This means that the game server database is down, or otherwise unreachable. This error has been logged and your Statbus administrators have been made aware of the issue.',
        'code' => 500,
        'skip' => true
      ]);
      $this->response = $this->response->withStatus(500);
      die($this->response->getBody());
    }
  }

  public function getFullURL($path){
    $base = trim($this->request->getUri()->getBaseUrl(), '/');
    return $base.$path;
  }
}
