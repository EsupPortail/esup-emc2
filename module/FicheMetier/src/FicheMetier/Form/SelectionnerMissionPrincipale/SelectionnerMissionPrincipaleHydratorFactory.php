<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Service\FicheMetierMission\FicheMetierMissionService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerMissionPrincipaleHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerMissionPrincipaleHydrator
    {
        /**
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var FicheMetierMissionService $ficheMetierMissionService
         */
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $ficheMetierMissionService = $container->get(FicheMetierMissionService::class);

        $hydrator = new SelectionnerMissionPrincipaleHydrator();
        $hydrator->setMissionPrincipaleService($missionPrincipaleService);
        $hydrator->setFicheMetierMissionService($ficheMetierMissionService);
        return $hydrator;
    }
}
