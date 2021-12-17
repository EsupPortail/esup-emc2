<?php

namespace Application\Service\SpecificiteActivite;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class SpecificiteActiviteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return SpecificiteActiviteService
     */
    public function __invoke(ContainerInterface $container) : SpecificiteActiviteService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $service = new SpecificiteActiviteService();
        $service->setEntityManager($entityManager);
        return $service;
    }
}