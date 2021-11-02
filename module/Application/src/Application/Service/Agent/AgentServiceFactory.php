<?php

namespace Application\Service\Agent;

use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AgentServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentService
     */
    public function __invoke(ContainerInterface $container) : AgentService
    {
        /**
         * @var EntityManager $entityManager
         * @var StructureService $structureService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $structureService = $container->get(StructureService::class);

        /** @var AgentService $service */
        $service = new AgentService();
        $service->setEntityManager($entityManager);
        $service->setStructureService($structureService);

        return $service;
    }
}