<?php

namespace Metier\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DomaineService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var DomaineService $service */
        $service = new DomaineService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}