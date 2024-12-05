<?php

namespace Agent\Service\AgentGrade;

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
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AgentGradeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}