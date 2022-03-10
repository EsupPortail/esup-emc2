<?php

namespace Autoform\Service\Champ;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ChampServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var ChampService $service */
        $service = new ChampService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}