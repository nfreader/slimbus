<?php
namespace Statbus\Middleware;

use Statbus\Controllers\UserController as User;
use Statbus\Controllers\AuthController as Auth;
class UserGuard {

  public function __construct($container) {
    $this->container = $container;
    $this->user = $container->get('user');
    $this->view = $container->get('view');
    $this->request = $container->get('request');
  }

  public function __invoke($request, $response, $next) {
    if(!$this->user->getCkey()) {
      $_SESSION['return_uri'] = (string) $this->request->getUri();
      $args = null;
      return (new Auth($this->container))->auth($request, $response, $args);
      // die("You do not have permission to access this page");
    }
    if (!$this->user->canAccessTGDB()) {
      return $this->view->render($response, 'base/error.tpl',[
        'message' => "You do not have permission to access this page.",
        'code'    => 403
      ]);
    }
    // if(!(new Auth($this->container))->doubleCheckRemote()){
    //   return $this->view->render($response, 'base/error.tpl',[
    //     'message' => "Your session has expired.",
    //     'code'    => 403
    //   ]);
    // }
    $this->view->getEnvironment()->addGlobal('classified', TRUE);
    $response = $next($request, $response);
    return $response;
  }
}