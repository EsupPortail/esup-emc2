<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Application\Service\Domaine\DomaineService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;

class PosteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var DomaineService $domaineService
         * @var StructureService $structureService
         * @var RessourceRhService $ressourceService
         */
        $agentService = $container->get(AgentService::class);
        $domaineService = $container->get(DomaineService::class);
        $structureService = $container->get(StructureService::class);
        $ressourceService = $container->get(RessourceRhService::class);


        $hydrator = new PosteHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setDomaineService($domaineService);
        $hydrator->setStructureService($structureService);
        $hydrator->setRessourceRhService($ressourceService);

        return $hydrator;
    }
}
