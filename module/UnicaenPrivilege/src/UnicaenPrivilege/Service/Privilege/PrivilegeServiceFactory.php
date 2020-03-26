<?php

namespace UnicaenPrivilege\Service\Privilege;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenPrivilege\Entity\Db\Privilege;

class PrivilegeServiceFactory {

    public function __invoke(ContainerInterface $container) {

        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $allConfig = $container->get('Config');

        /** @var PrivilegeService $service */
        $service = new PrivilegeService();
        $service->setEntityManager($entityManager);
        $service->setPrivilegeEntityClass($allConfig['unicaen-auth']['privilege_entity_class'] ?? Privilege::class);
        return $service;
    }
}