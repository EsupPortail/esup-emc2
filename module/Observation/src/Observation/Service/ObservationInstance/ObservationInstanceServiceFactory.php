<?php

namespace Observation\Service\ObservationInstance;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ObservationInstanceServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservationInstanceService
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ObservationInstanceService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}