<?php

namespace UnicaenDocument\Controller;

use Interop\Container\ContainerInterface;

class ContenuControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ContenuController
     */
    public function __invoke(ContainerInterface $container)
    {
        $controller = new ContenuController();
        return $controller;
    }
}