<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Service\MissionElement\MissionElementService;
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
         * @var MissionElementService $missionElementService
         */
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $missionElementService = $container->get(MissionElementService::class);

        $hydrator = new SelectionnerMissionPrincipaleHydrator();
        $hydrator->setMissionPrincipaleService($missionPrincipaleService);
        $hydrator->setMissionElementService($missionElementService);
        return $hydrator;
    }
}
