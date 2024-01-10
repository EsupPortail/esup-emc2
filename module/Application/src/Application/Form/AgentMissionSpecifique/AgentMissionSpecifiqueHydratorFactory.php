<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class AgentMissionSpecifiqueHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentMissionSpecifiqueHydrator
    {
        /**
         * @var AgentService $agentService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);

        $hydrator = new AgentMissionSpecifiqueHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setMissionSpecifiqueService($missionSpecifiqueService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}
