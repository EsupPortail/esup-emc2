<?php

namespace Application\Service\CompetenceType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CompetenceTypeServiceFactory {

    public function __invoke(ContainerInterface $container) : CompetenceTypeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var CompetenceTypeService $service */
        $service = new CompetenceTypeService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}