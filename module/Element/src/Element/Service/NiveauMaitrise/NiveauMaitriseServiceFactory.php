<?php

namespace Element\Service\NiveauMaitrise;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauMaitriseServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauMaitriseService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauMaitriseService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new NiveauMaitriseService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}