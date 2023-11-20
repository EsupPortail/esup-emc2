<?php

namespace Formation\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DomaineService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new DomaineService();
        $service->setObjectManager($entityManager);
        return $service;
    }
}