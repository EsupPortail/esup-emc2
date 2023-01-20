<?php

namespace Formation\Service\PlanDeFormation;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PlanDeFormationServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return PlanDeFormationService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PlanDeFormationService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new PlanDeFormationService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}