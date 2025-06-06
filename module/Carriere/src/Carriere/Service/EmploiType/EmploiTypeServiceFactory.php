<?php

namespace Carriere\Service\EmploiType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiTypeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return EmploiTypeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new EmploiTypeService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}
