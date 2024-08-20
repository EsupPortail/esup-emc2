<?php

namespace Carriere\Service\Corps;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CorpsServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorpsService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorpsService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CorpsService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}
