<?php

namespace Autoform\Controller;

use Interop\Container\ContainerInterface;

class IndexControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return IndexController
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var IndexController $controller*/
        $controller = new IndexController();
        return $controller;
    }
}