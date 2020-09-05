<?php
namespace Statbus\Middleware;

use Statbus\Controllers\UserController as User;
use Statbus\Controllers\AuthController as Auth;
class UserGuard {

  public function __construct($container, $level = 2) {
    $this->container = $container;
    $this->user = $container->get('user');
    $this->view = $container->get('view');
    $this->request = $container->get('request');
    $this->settings = $container->get('settings');
    $this->level = $level;
  }

  public function __invoke($request, $response, $next) {
    if(!$this->settings['statbus']['auth']['remote_auth'] && !$this->settings['statbus']['ip_auth']){
      return $this->view->render($response, 'base/error.tpl',[
          'message' => "No authentication mechanisms specified.",
          'code'    => 403
        ]);
    }
    if(!$this->user) {
      $_SESSION['return_uri'] = (string) $this->request->getUri();
      $args = null;
      return (new Auth($this->container))->auth($request, $response, $args);
    }
    switch ($this->level){
      case 1:
      default:
      if (!$this->user) {
        return $this->view->render($response, 'base/error.tpl',[
          'message' => "You must be logged in to access this page",
          'code'    => 403
        ]);
      }
      break;

      case 2:
        if (!$this->user->canAccessTGDB) {
          return $this->view->render($response, 'base/error.tpl',[
            'message' => "You do not have permission to access this page.",
            'code'    => 403
          ]);
          die();
        }
        $this->view->getEnvironment()->addGlobal('classified', TRUE);
      break;
    }
    $response = $next($request, $response);
    return $response;
  }
}