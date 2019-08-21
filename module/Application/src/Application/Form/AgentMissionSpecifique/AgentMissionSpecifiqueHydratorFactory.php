<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class AgentMissionSpecifiqueHydratorFactory
{

    public function __invoke(HydratorPluginManager $manager)
    {

        /**
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         * @var StructureService $structureService
         */
        $agentService     = $manager->getServiceLocator()->get(AgentService::class);
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);
        $structureService = $manager->getServiceLocator()->get(StructureService::class);

        /** @var AgentMissionSpecifiqueHydrator $hydrator */
        $hydrator = new AgentMissionSpecifiqueHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setRessourceRhService($ressourceService);
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }
}
