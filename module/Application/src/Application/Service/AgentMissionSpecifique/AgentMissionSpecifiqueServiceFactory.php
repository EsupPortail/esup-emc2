<?php

namespace Application\Service\AgentMissionSpecifique;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;

class AgentMissionSpecifiqueServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
        $service->setObjectManager($entityManager);
        $service->setStructureService($structureService);
        return $service;
    }
}