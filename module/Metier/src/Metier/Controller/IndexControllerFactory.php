<?php

namespace Metier\Controller;

use Psr\Container\ContainerInterface;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container) : IndexController
    {
        $controller = new IndexController();
        return $controller;
    }
}