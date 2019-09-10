<?php

namespace Application\Service\Fonction;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class FonctionServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var FonctionService $service */
        $service = new FonctionService();
        $service->setEntityManager($entityManager);
        return $service;

    }
}