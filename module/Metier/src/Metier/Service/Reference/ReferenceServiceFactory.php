<?php

namespace Metier\Service\Reference;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferenceServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferenceService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new ReferenceService();
        $service->setObjectManager($entityManager);

        return $service;
    }
}