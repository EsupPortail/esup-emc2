<?php

namespace Carriere\Service\Mobilite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class MobiliteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MobiliteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MobiliteService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MobiliteService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}