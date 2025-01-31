<?php

namespace Application\Controller;

use FichePoste\Service\FichePoste\FichePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FichePoste\Service\MissionAdditionnelle\MissionAdditionnelleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SpecificiteControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificiteController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SpecificiteController
    {
        /**
         * @var FichePosteService $fichePosteService
         * @var MissionAdditionnelleService $missionAdditionnelleService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var SpecificitePosteService $specificitePosteService
         */
        $fichePosteService = $container->get(FichePosteService::class);
        $missionAdditionnelleService = $container->get(MissionAdditionnelleService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);

        $controller = new SpecificiteController();
        $controller->setFichePosteService($fichePosteService);
        $controller->setMissionAdditionnelleService($missionAdditionnelleService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setSpecificitePosteService($specificitePosteService);
        return $controller;
    }

}