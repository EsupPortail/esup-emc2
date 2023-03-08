<?php

namespace Referentiel\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Synchronisation\SynchronisationService;

class SynchronisationConsoleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return SynchronisationConsoleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SynchronisationConsoleController
    {
        $synchronisationService = $container->get(SynchronisationService::class);
        $configs = $container->get('Config')['synchros'];

        $controller = new SynchronisationConsoleController();
        $controller->setConfigs($configs);
        $controller->setSynchronisationService($synchronisationService);
        return $controller;
    }
}