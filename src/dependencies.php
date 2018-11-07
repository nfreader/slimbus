<?php
// DIC configuration

$container = $app->getContainer();
$settings = $container->get('settings');

// DB
$container['DB'] = function ($c) {
  $settings = $c->get('settings')['database']['primary'];
  return (new Statbus\Controllers\DBController($settings))->db;
};

// User
$container['user'] = function ($container) {
  $user = (new Statbus\Controllers\UserController($container));
  return $user;
};

// Register component on container
$container['view'] = function ($container) {
  $settings = $container->get('settings')['twig'];
  $view = new \Slim\Views\Twig($settings['template_path'], [
      'debug' => $settings['twig_debug'],
      'cache' => $settings['template_cache']
  ]);

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));
  $view->addExtension(new \Twig_Extension_Debug());

  //Fancy timestamp filter
  $twigTimestampFilter = new \Twig_Filter('timestamp', function ($string) {
    $string = date('Y-m-d H:i:s', strtotime($string));
    $return = "<span class='timestamp'>";
    $return.= "<time datetime='$string' title='$string' ";
    $return.= "data-toggle='tooltip'>$string</time></span>";
    return $return;
  });
  $view->getEnvironment()->addFilter($twigTimestampFilter);

  //Global statbus settings
  $view->getEnvironment()->addGlobal('statbus', $container->get('settings')['statbus']);
  //User added by the UserController when it gets instantiated
  return $view;
};

//Guzzle 
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Psr7\InflateStream;
use Kevinrob\GuzzleCache\CacheMiddleware;
use League\Flysystem\Adapter\Local;
use Kevinrob\GuzzleCache\KeyValueHttpHeader;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;
use Doctrine\Common\Cache\FilesystemCache;

$container['guzzle'] = function ($container) {
  $stack = HandlerStack::create();
  $stack->push(
      new CacheMiddleware(new GreedyCacheStrategy(new DoctrineCacheStorage(new FilesystemCache(__DIR__.'/../tmp/guzzle')),3600)),'greedy-cache'
    );
  $client = new Client([
    'handler'        => $stack,
    'headers'        => [
      'Accept-Encoding' => 'gzip',
      'User-Agent'      => 'Statbus'
    ],
  ]);
  return $client;
};

function pick($list) {
  if (!is_array($list)) {
    $list = explode(',',$list);
  }
  return $list[floor(rand(0,count($list)-1))];
}
