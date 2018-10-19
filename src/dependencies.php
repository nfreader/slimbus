<?php
// DIC configuration

$container = $app->getContainer();

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
    $twigTimestampFilter = new \Twig_Filter('timestamp', function ($string) {
      $string = date('Y-m-d H:i:s', strtotime($string));
      $return = "<span class='timestamp'>";
      $return.= "<time datetime='$string' title='$string' ";
      $return.= "data-toggle='tooltip'>$string</time></span>";
      return $return;
    });
    $view->getEnvironment()->addFilter($twigTimestampFilter);

    $twigCkeyLink = new \Twig_Function('ckey', function ($name, $ckey) {
      $return = "<a href='#'>$name<small class='text-muted'>/$ckey</small></a>";
      return $return;
    }, array('is_safe' => array('html')));
    $view->getEnvironment()->addFunction($twigCkeyLink);
    return $view;
};

// DB
$container['DB'] = function ($c) {
    $settings = $c->get('settings')['database']['primary'];
    return (new Statbus\Controllers\DBController($settings))->db;
};

