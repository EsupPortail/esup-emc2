<?php

namespace FichePoste\Service\MissionAdditionnelle;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionAdditionnelleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionAdditionnelleService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionAdditionnelleService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionAdditionnelleService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}