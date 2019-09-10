<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class AgentMissionSpecifiqueHydratorFactory
{

    public function __invoke(ContainerInterface $container)
    {

        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         * @var StructureService $structureService
         */
        $agentService     = $container->get(AgentService::class);
        $ressourceService = $container->get(RessourceRhService::class);
        $structureService = $container->get(StructureService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = new AgentMissionSpecifiqueHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setRessourceRhService($ressourceService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}
