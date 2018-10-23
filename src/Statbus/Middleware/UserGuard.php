<?php
namespace Statbus\Middleware;

use Statbus\Controllers\UserController as User;

class UserGuard {

  public function __construct($container) {
    $this->container = $container;
    $this->user = $container->get('user');
  }

  public function __invoke($request, $response, $next) {
    if(!$this->user->canAccessTGDB()) {
      die("You do not have permission to access this page");
    }
    $response = $next($request, $response);
    return $response;
  }
}