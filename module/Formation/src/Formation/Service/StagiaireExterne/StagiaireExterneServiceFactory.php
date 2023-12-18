<?php

namespace Formation\Service\StagiaireExterne;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class StagiaireExterneServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return StagiaireExterneService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): StagiaireExterneService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new StagiaireExterneService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}