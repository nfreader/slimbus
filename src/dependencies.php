<?php
// DIC configuration

$container = $app->getContainer();

//Inject Statbus settings because the default app instantiator doesn't work(?!)
$settings = $container->get('settings');
$settings->replace(['statbus' => require __DIR__ . '/../src/conf/Statbus.php']);

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
  // $user = $container->get('user')->fetchUser();
  // $view->getEnvironment()->addGlobal('user',$user);
  return $view;
};


