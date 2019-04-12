<?php

namespace Utilisateur\Service\Privilege;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class PrivilegeServiceFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator) {

        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

        /** @var PrivilegeService $service */
        $service = new PrivilegeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}