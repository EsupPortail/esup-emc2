<?php

namespace Application\Service\Metier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MetierServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var MetierService $service */
        $service = new MetierService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}