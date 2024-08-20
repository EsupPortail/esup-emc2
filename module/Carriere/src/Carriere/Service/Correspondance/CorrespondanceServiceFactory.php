<?php

namespace Carriere\Service\Correspondance;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CorrespondanceServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorrespondanceService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CorrespondanceService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}
