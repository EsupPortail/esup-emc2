<?php

namespace Formation\Controller;

use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationConsoleControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationConsoleController
    {
        /**
         * @var SessionService $sessionService
         * @var ParametreService $parametreService
         */
        $sessionService = $container->get(SessionService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new FormationConsoleController();
        $controller->setSessionService($sessionService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}