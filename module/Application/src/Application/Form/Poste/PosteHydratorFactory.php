<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Carriere\Service\Correspondance\CorrespondanceService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
use Structure\Service\Structure\StructureService;

class PosteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var CorrespondanceService $correspondanceService
         * @var DomaineService $domaineService
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
