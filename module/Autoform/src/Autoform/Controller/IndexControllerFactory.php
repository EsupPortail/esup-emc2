<?php

namespace Autoform\Controller;

use Interop\Container\ContainerInterface;

class IndexControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var IndexController $controller*/
        $controller = new IndexController();
        return $controller;
    }
}