<?php

namespace Octopus\Service\Structure;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class StructureServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_octopus');

        /** @var StructureService $service */
        $service = new StructureService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}