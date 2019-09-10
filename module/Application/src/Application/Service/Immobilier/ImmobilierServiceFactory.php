<?php

namespace Application\Service\Immobilier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ImmobilierServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ImmobilierService $service */
        $service = new ImmobilierService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}