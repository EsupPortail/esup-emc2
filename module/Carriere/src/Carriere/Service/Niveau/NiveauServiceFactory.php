<?php

namespace Carriere\Service\Niveau;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param $objectManager
     * @return NiveauService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container, $objectManager): NiveauService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new NiveauService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}