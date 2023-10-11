<?php

namespace FicheMetier\Service\MissionActivite;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionActiviteServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return MissionActiviteService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionActiviteService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionActiviteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}