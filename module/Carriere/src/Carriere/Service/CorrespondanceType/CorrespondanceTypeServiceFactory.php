<?php

namespace Carriere\Service\CorrespondanceType;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CorrespondanceTypeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorrespondanceTypeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorrespondanceTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new CorrespondanceTypeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}