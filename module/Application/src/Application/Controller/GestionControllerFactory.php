<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;

class GestionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return GestionController
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var GestionController $controller */
        $controller = new GestionController();
        return $controller;
    }
}