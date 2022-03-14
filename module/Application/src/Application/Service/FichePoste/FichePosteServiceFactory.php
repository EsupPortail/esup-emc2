<?php

namespace Application\Service\FichePoste;

use Application\Service\Agent\AgentService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;

class FichePosteServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var SpecificitePosteService $specificitePosteService
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);

        /** @var FichePosteService $service */
        $service = new FichePosteService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setSpecificitePosteService($specificitePosteService);
        $service->setStructureService($structureService);

        return $service;
    }
}