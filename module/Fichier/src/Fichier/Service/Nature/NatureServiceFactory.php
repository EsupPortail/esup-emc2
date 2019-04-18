<?php

namespace Fichier\Service\Nature;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class NatureServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var NatureService $service */
        $service = new NatureService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}