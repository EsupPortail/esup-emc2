<?php

namespace Element\Controller;

use Interop\Container\ContainerInterface;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container) : IndexController
    {
        $controller = new IndexController();
        return $controller;
    }
}