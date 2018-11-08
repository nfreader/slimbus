<?php

namespace Statbus\Controllers;
use Psr\Container\ContainerInterface;
use Statbus\Controllers\Controller as Controller;


class AuthController Extends Controller{

  // protected $DB;
  
  private $site_private_token;
  private $session_private_token;
  private $return_uri;


  public function __construct(ContainerInterface $container) {
    parent::__construct($container);
    $this->settings = $container->get('settings')['statbus']['auth'];
    if(!$this->settings['remote_auth']) {
      return $this->view->render($this->response, 'base/error.tpl', [
        'message' => "Authentication not supported",
        'code'    => 501,
      ]);
    } else {
      $this->remote = $this->settings['remote_auth'];
      $this->AuthUrl = $this->remote."oauth_create_session.php";
      $this->TokenUrl = $this->remote."oauth.php";
      $this->AuthSessionUrl = $this->remote."oauth_get_session_info.php";
      $this->return_uri = $this->request->getUri()->getBaseUrl().$this->router->pathFor('auth_return');

      if(isset($_SESSION['site_private_token'])){
        $this->site_private_token = $_SESSION['site_private_token'];
      } else {
        $this->site_private_token = $this->generateToken(TRUE);
        $_SESSION['site_private_token'] = $this->site_private_token;
      }
    }
  }

  public function generateToken(bool $secure=TRUE){
    $r_bytes = openssl_random_pseudo_bytes(5120, $secure);
    return hash('sha512', $r_bytes);
  }

  public function auth($request, $response, $args) {
    if(!$this->settings['remote_auth']) {
      return false;
    }
    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', $this->AuthUrl, [
      'query' => [
        'site_private_token' => $this->site_private_token,
        'return_uri' => $this->return_uri,
      ]
    ]);
    $res = json_decode($res->getBody());

    $this->response = $res;
    $_SESSION['session_public_token']  = $res->session_public_token;
    $_SESSION['session_private_token'] = $res->session_private_token;
    $this->session_public_token  = $_SESSION['session_public_token'];
    $this->session_private_token = $_SESSION['session_private_token'];

    return $this->view->render($response, 'auth/confirm.tpl');
  }

  public function auth_redirect(){
    $this->session_public_token  = $_SESSION['session_public_token'];
    $this->session_private_token = $_SESSION['session_private_token'];

    return $this->response->withRedirect("$this->TokenUrl?session_public_token=".urlencode($this->session_public_token));
  }

  public function auth_return($request, $response, $args) {
    $this->site_private_token = $_SESSION['site_private_token'];
    $this->session_private_token = $_SESSION['session_private_token'];
    $client = new \GuzzleHttp\Client();
    $res = $client->request('GET', $this->AuthSessionUrl, [
      'query'=> [
        'site_private_token'    => $this->site_private_token,
        'session_private_token' => $this->session_private_token,
      ]
    ]);
    $res = json_decode($res->getBody());
    if('OK' != $res->status){
      die("Something went wrong!");
    }
    foreach($res as $k => $v){
      $_SESSION['sb'][$k] = $v;
    }
    if(isset($_SESSION['return_uri'])) {
      $return_uri = $_SESSION['return_uri'];
    } else {
      $return_uri = false;
    }
    return $this->view->render($response, 'auth/return.tpl',[
      'return_uri' => $return_uri
    ]);
  }

  public function doubleCheckRemote() {
    if ($_SESSION['canary'] < time() - 300) {
      $client = new \GuzzleHttp\Client();
      $res = $client->request('GET', $this->AuthSessionUrl, [
        'query'=> [
          'site_private_token'    => $this->site_private_token,
          'session_private_token' => $this->session_private_token,
        ]
      ]);
      $res = json_decode($res->getBody());
      if('OK' != $res->status){
        session_unset();
        session_destroy();
        session_start();
        return false;
      } else {
        return true;
      }
    }
  }

  public function logout($request, $response, $args) {
    $_SESSION = '';
    session_destroy();
    $this->user = null;
    return $this->view->render($response, 'auth/logout.tpl');
  }
}