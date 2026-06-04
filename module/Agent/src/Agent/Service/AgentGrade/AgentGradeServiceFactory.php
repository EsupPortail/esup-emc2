<?php

namespace Agent\Service\AgentGrade;

use Application\Service\SqlHelper\SqlHelperService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentGradeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentGradeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentGradeService
    {
        /**
         * @var EntityManager $entityManager
         * @var SqlHelperService $sqlHelperService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sqlHelperService = $container->get(SqlHelperService::class);

        $service = new AgentGradeService();
        $service->setObjectManager($entityManager);
        $service->setSqlHelperService($sqlHelperService);
        return $service;
    }
}