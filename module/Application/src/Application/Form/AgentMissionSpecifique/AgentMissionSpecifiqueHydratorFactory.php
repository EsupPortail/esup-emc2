<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class AgentMissionSpecifiqueHydratorFactory
{

    public function __invoke(ContainerInterface $container)
    {

        /**
         * @var AgentService $agentService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var StructureService $structureService
         */
        $agentService     = $container->get(AgentService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = new AgentMissionSpecifiqueHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setMissionSpecifiqueService($missionSpecifiqueService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}
