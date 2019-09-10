<?php

namespace Application\Service\Domaine;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class DomaineServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var DomaineService $service */
        $service = new DomaineService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}