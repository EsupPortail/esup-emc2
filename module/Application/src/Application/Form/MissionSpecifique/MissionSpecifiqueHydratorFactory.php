<?php

namespace Application\Form\MissionSpecifique;

use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Interop\Container\ContainerInterface;

class MissionSpecifiqueHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MissionSpecifiqueService $missionSpecifiqueService  */
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);

        /** @var MissionSpecifiqueHydrator $hydrator */
        $hydrator = new MissionSpecifiqueHydrator();
        $hydrator->setMissionSpecifiqueService($missionSpecifiqueService);
        return $hydrator;
    }
}