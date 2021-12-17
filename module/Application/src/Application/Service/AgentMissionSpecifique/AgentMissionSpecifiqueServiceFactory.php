<?php

namespace Application\Service\AgentMissionSpecifique;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentMissionSpecifiqueServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentMissionSpecifiqueService
     */
    public function __invoke(ContainerInterface $container) : AgentMissionSpecifiqueService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        $service = new AgentMissionSpecifiqueService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }
}