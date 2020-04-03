<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;

class AdministrationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AdministrationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var AdministrationController $controller */
        $controller = new AdministrationController();
        return $controller;
    }
}