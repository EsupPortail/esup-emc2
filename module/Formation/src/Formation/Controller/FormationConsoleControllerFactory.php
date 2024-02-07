<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationConsoleControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationConsoleController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var ParametreService $parametreService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new FormationConsoleController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}