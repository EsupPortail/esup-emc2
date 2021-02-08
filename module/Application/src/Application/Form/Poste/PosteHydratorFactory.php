<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Application\Service\Correspondance\CorrespondanceService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;

class PosteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var DomaineService $domaineService
         * @var CorrespondanceService $correspondanceService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $correspondanceService = $container->get(CorrespondanceService::class);
        $domaineService = $container->get(DomaineService::class);
        $structureService = $container->get(StructureService::class);

        $hydrator = new PosteHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setCorrespondanceService($correspondanceService);
        $hydrator->setDomaineService($domaineService);
        $hydrator->setStructureService($structureService);

        return $hydrator;
    }
}
