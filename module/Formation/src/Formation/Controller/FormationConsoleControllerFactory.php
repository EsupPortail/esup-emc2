<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationConsoleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationConsoleController
     */
    public function __invoke(ContainerInterface $container)
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