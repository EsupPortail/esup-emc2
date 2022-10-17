<?php

namespace Formation\Service\Abonnement;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbonnementServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return AbonnementService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AbonnementService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new AbonnementService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}