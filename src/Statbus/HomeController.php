<?php

namespace Statbus;

use Psr\Container\ContainerInterface;

class HomeController {
   protected $container;
   protected $DB;

   // constructor receives container instance
   public function __construct(ContainerInterface $container) {
       $this->container = $container;
       $this->DB = $this->container->get('DB');
   }

   public function home($request, $response, $args) {
        var_dump($this->DB);
        var_dump($args);
        return $response;
   }

   public function contact($request, $response, $args) {
        // your code
        // to access items in the container... $this->container->get('');
        return $response;
   }
}