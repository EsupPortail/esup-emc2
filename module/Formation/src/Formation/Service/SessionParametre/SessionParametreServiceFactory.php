<?php

namespace Formation\Service\SessionParametre;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SessionParametreServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return SessionParametreService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SessionParametreService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new SessionParametreService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}