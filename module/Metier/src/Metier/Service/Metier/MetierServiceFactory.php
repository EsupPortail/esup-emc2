<?php

namespace Metier\Service\Metier;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MetierServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierService
     */
    public function __invoke(ContainerInterface $container) : MetierService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var MetierService $service */
        $service = new MetierService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}