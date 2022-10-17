<?php

namespace Fichier\Service\Nature;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class NatureServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var NatureService $service */
        $service = new NatureService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}