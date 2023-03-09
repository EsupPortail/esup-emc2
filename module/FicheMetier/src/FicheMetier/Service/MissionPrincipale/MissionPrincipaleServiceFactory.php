<?php

namespace FicheMetier\Service\MissionPrincipale;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionPrincipaleService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionPrincipaleService
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new MissionPrincipaleService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}