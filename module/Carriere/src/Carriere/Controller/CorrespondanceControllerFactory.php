<?php

namespace Carriere\Controller;

use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class CorrespondanceControllerFactory {

    public function __invoke(ContainerInterface $container) : CorrespondanceController
    {
        /**
         * @var CorrespondanceService $correspondanceService
         * @var ParametreService $parametreService
         */
        $correspondanceService = $container->get(CorrespondanceService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new CorrespondanceController();
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}