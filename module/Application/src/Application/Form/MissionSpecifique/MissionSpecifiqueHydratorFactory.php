<?php

namespace Application\Form\MissionSpecifique;

use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\MissionSpecifiqueTheme\MissionSpecifiqueThemeService;
use Application\Service\MissionSpecifiqueType\MissionSpecifiqueTypeService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueHydratorFactory {

    public function __invoke(ContainerInterface $container) : MissionSpecifiqueHydrator
    {
        /**
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var MissionSpecifiqueThemeService $missionSpecifiqueThemeService
         * @var MissionSpecifiqueTypeService $missionSpecifiqueTypeService
         */
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $missionSpecifiqueThemeService = $container->get(MissionSpecifiqueThemeService::class);
        $missionSpecifiqueTypeService = $container->get(MissionSpecifiqueTypeService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = new MissionSpecifiqueHydrator();
        $hydrator->setMissionSpecifiqueService($missionSpecifiqueService);
        $hydrator->setMissionSpecifiqueThemeService($missionSpecifiqueThemeService);
        $hydrator->setMissionSpecifiqueTypeService($missionSpecifiqueTypeService);
        return $hydrator;
    }
}