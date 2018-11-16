<?php
namespace Statbus\Middleware;
class OpenGraph {

  public function __construct($container) {
    $this->container = $container;
    $this->view = $container->get('view');
    // var_dump($this->view);
  }

  public function __invoke($request, $response, $next) {
    $response->getBody()->write('BEFORE');
    $response = $next($request, $response);
    $response->getBody()->write('AFTER');
    return $response;
  }
}