<?php

namespace Structure\Service\Type;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

class TypeServiceFactory {

    public function __invoke(ContainerInterface $container) : TypeService
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new TypeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}