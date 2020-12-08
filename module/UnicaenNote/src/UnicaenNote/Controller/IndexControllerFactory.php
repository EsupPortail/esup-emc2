<?php

namespace UnicaenNote\Controller;

use Interop\Container\ContainerInterface;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        $controller = new IndexController();
        return $controller;
    }

}