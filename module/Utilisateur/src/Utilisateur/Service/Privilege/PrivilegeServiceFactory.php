<?php

namespace Utilisateur\Service\Privilege;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PrivilegeServiceFactory {

    public function __invoke(ContainerInterface $container) {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var PrivilegeService $service */
        $service = new PrivilegeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}