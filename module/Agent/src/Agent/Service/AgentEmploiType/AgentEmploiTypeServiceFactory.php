<?php

namespace Agent\Service\AgentEmploiType;

use Application\Service\SqlHelper\SqlHelperService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentEmploiTypeServiceFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentEmploiTypeService
    {
        /**
         * @var EntityManager $entityManager
         * @var SqlHelperService $sqlHelperService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sqlHelperService = $container->get(SqlHelperService::class);

        $service = new AgentEmploiTypeService();
        $service->setObjectManager($entityManager);
        $service->setSqlHelperService($sqlHelperService);
        return $service;
    }
}
